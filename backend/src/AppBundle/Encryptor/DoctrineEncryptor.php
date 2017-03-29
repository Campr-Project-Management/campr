<?php

namespace AppBundle\Encryptor;

use Ambta\DoctrineEncryptBundle\Encryptors\EncryptorInterface;

class DoctrineEncryptor implements EncryptorInterface
{
    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var string
     */
    private $initializationVector;

    /**
     * {@inheritdoc}
     */
    public function __construct($key)
    {
        $this->setSecretKey($key);
        $this->initializationVector = mcrypt_create_iv(
            mcrypt_get_iv_size(MCRYPT_RIJNDAEL_192, MCRYPT_MODE_ECB),
            MCRYPT_RAND
        );
    }

    public function setSecretKey($key)
    {
        $this->secretKey = md5($key);
    }

    /**
     * {@inheritdoc}
     */
    public function encrypt($data)
    {
        if (is_string($data)) {
            return trim(base64_encode(mcrypt_encrypt(
                MCRYPT_RIJNDAEL_192,
                $this->secretKey,
                $data,
                MCRYPT_MODE_ECB,
                $this->initializationVector
            ))).'<ENC>';
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt($data)
    {
        if (is_string($data)) {
            return trim(mcrypt_decrypt(
                MCRYPT_RIJNDAEL_192,
                $this->secretKey,
                base64_decode($data),
                MCRYPT_MODE_ECB,
                $this->initializationVector
            ));
        }

        return $data;
    }
}
