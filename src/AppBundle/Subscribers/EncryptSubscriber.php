<?php

namespace AppBundle\Subscribers;

use Ambta\DoctrineEncryptBundle\Subscribers\DoctrineEncryptSubscriber;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Util\ClassUtils;
use ReflectionClass;
use Ambta\DoctrineEncryptBundle\Encryptors\EncryptorInterface;

/**
 * Doctrine event subscriber which encrypt/decrypt entities.
 */
class EncryptSubscriber extends DoctrineEncryptSubscriber
{
    /**
     * Encryptor interface namespace.
     */
    const ENCRYPTOR_INTERFACE_NS = 'Ambta\DoctrineEncryptBundle\Encryptors\EncryptorInterface';

    /**
     * Encrypted annotation full name.
     */
    const ENCRYPTED_ANN_NAME = 'Ambta\DoctrineEncryptBundle\Configuration\Encrypted';

    /**
     * Encryptor.
     *
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * Annotation reader.
     *
     * @var \Doctrine\Common\Annotations\Reader
     */
    private $annReader;

    /**
     * Secret key.
     *
     * @var string
     */
    private $secretKey;

    /**
     * Used for restoring the encryptor after changing it.
     *
     * @var string
     */
    private $restoreEncryptor;

    /**
     * Count amount of decrypted values in this service.
     *
     * @var int
     */
    public $decryptCounter = 0;

    /**
     * Count amount of encrypted values in this service.
     *
     * @var int
     */
    public $encryptCounter = 0;

    /**
     * Initialization of subscriber.
     *
     * @param Reader                  $annReader
     * @param string                  $encryptorClass The encryptor class.  This can be empty if a service is being provided
     * @param string                  $secretKey      The secret key
     * @param EncryptorInterface|null $service        (Optional)  An EncryptorInterface.
     *
     * This allows for the use of dependency injection for the encrypters
     */
    public function __construct(Reader $annReader, $encryptorClass, $secretKey, EncryptorInterface $service = null)
    {
        $this->annReader = $annReader;
        $this->secretKey = $secretKey;

        if ($service instanceof EncryptorInterface) {
            $this->encryptor = $service;
        } else {
            $this->encryptor = $this->encryptorFactory($encryptorClass, $secretKey);
        }

        $this->restoreEncryptor = $this->encryptor;
    }

    public function setSecretKey($entity)
    {
        if (method_exists($entity, 'getEncryptionKey') && $entity->getEncryptionKey()) {
            $this->encryptor->setSecretKey($entity->getEncryptionKey());
        } else if (method_exists($entity, 'getProject') && $entity->getProject() && $entity->getProject()->getEncryptionKey()) {
            $this->encryptor->setSecretKey($entity->getProject()->getEncryptionKey());
        } else {
            $this->encryptor->setSecretKey($this->secretKey);
        }
    }

    /**
     * Process (encrypt/decrypt) entities fields.
     *
     * @param object $entity             doctrine entity
     * @param bool   $isEncryptOperation If true - encrypt, false - decrypt entity
     *
     * @throws \RuntimeException
     *
     * @return object|null
     */
    public function processFields($entity, $isEncryptOperation = true)
    {
        if (!empty($this->encryptor)) {
            // sets encryptor secret key if encryption key field exists on entity
            $this->setSecretKey($entity);

            //Check which operation to be used
            $encryptorMethod = $isEncryptOperation ? 'encrypt' : 'decrypt';

            //Get the real class, we don't want to use the proxy classes
            if (strstr(get_class($entity), 'Proxies')) {
                $realClass = ClassUtils::getClass($entity);
            } else {
                $realClass = get_class($entity);
            }

            //Get ReflectionClass of our entity
            $reflectionClass = new ReflectionClass($realClass);
            $properties = $this->getClassProperties($realClass);

            //Foreach property in the reflection class
            foreach ($properties as $refProperty) {
                if ($this->annReader->getPropertyAnnotation($refProperty, 'Doctrine\ORM\Mapping\Embedded')) {
                    $this->handleEmbeddedAnnotation($entity, $refProperty, $isEncryptOperation);
                    continue;
                }
                /**
                 * If followed standards, method name is getPropertyName, the propertyName is lowerCamelCase
                 * So just uppercase first character of the property, later on get and set{$methodName} wil be used.
                 */
                $methodName = ucfirst($refProperty->getName());

                /*
                 * If property is an normal value and contains the Encrypt tag, lets encrypt/decrypt that property
                 */
                if ($this->annReader->getPropertyAnnotation($refProperty, self::ENCRYPTED_ANN_NAME)) {

                    /*
                     * If it is public lets not use the getter/setter
                     */
                    if ($refProperty->isPublic()) {
                        $propName = $refProperty->getName();
                        $entity->$propName = $this->encryptor->$encryptorMethod($refProperty->getValue());
                    } else {
                        //If private or protected check if there is an getter/setter for the property, based on the $methodName
                        if ($reflectionClass->hasMethod($getter = 'get'.$methodName) && $reflectionClass->hasMethod($setter = 'set'.$methodName)) {

                            //Get the information (value) of the property
                            try {
                                $getInformation = $entity->$getter();
                            } catch (\Exception $e) {
                                $getInformation = null;
                            }

                            /*
                             * Then decrypt, encrypt the information if not empty, information is an string and the <ENC> tag is there (decrypt) or not (encrypt).
                             * The <ENC> will be added at the end of an encrypted string so it is marked as encrypted. Also protects against double encryption/decryption
                             */
                            if ($encryptorMethod == 'decrypt') {
                                if (!is_null($getInformation) and !empty($getInformation)) {
                                    if (substr($getInformation, -5) == '<ENC>') {
                                        ++$this->decryptCounter;
                                        $currentPropValue = $this->encryptor->decrypt(substr($getInformation, 0, -5));
                                        $entity->$setter($currentPropValue);
                                    }
                                }
                            } else {
                                if (!is_null($getInformation) and !empty($getInformation)) {
                                    if (substr($entity->$getter(), -5) != '<ENC>') {
                                        ++$this->encryptCounter;
                                        $currentPropValue = $this->encryptor->encrypt($entity->$getter());
                                        $entity->$setter($currentPropValue);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return $entity;
        }

        return null;
    }

    private function handleEmbeddedAnnotation($entity, $embeddedProperty, $isEncryptOperation = true)
    {
        $reflectionClass = new ReflectionClass($entity);
        $propName = $embeddedProperty->getName();
        $methodName = ucfirst($propName);

        if ($embeddedProperty->isPublic()) {
            $embeddedEntity = $embeddedProperty->getValue();
        } else {
            if ($reflectionClass->hasMethod($getter = 'get'.$methodName) && $reflectionClass->hasMethod($setter = 'set'.$methodName)) {

                //Get the information (value) of the property
                try {
                    $embeddedEntity = $entity->$getter();
                } catch (\Exception $e) {
                    $embeddedEntity = null;
                }
            }
        }
        if ($embeddedEntity) {
            $this->processFields($embeddedEntity, $isEncryptOperation);
        }
    }

    /**
     * Encryptor factory. Checks and create needed encryptor.
     *
     * @param string $classFullName Encryptor namespace and name
     * @param string $secretKey     Secret key for encryptor
     *
     * @return EncryptorInterface
     *
     * @throws \RuntimeException
     */
    private function encryptorFactory($classFullName, $secretKey)
    {
        $refClass = new \ReflectionClass($classFullName);
        if ($refClass->implementsInterface(self::ENCRYPTOR_INTERFACE_NS)) {
            return new $classFullName($secretKey);
        } else {
            throw new \RuntimeException('Encryptor must implements interface EncryptorInterface');
        }
    }
}
