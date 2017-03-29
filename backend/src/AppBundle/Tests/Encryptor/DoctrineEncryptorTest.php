<?php

namespace AppBundle\Tests\Encryptor;

use AppBundle\Encryptor\DoctrineEncryptor;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineEncryptorTest extends KernelTestCase
{
    private $encryptor;

    protected function setUp()
    {
        $this->encryptor = new DoctrineEncryptor('ThisTokenIsNotSoSecretChangeIt');
    }

    protected function tearDown()
    {
        unset($this->encryptor);
    }

    /**
     * @dataProvider encryptProvider
     *
     * @param string $data
     */
    public function testEncrypt($data, $expected)
    {
        $this->assertEquals(
            $expected,
            $this->encryptor->encrypt($data)
        );
    }

    /**
     * @dataProvider decryptProvider
     *
     * @param string $data
     */
    public function testDecrypt($data, $expected)
    {
        $this->assertEquals(
            $expected,
            $this->encryptor->decrypt($data)
        );
    }

    /**
     * @return array
     */
    public function encryptProvider()
    {
        return [
            [
                'doctrine_encryption',
                'ol/UWEoCjColRqcSPX0o9s9v4bJmzy+M<ENC>',
            ],
            [
                '1234567890',
                '0+/bKg0l4OUIW5hMFgm9uqe1aChUQ2ew<ENC>',
            ],
            [
                'string_123456',
                'IlTvWisccthAKZu+KnyFncOFToFC7RMz<ENC>',
            ],
        ];
    }

    /**
     * @return array
     */
    public function decryptProvider()
    {
        return [
            [
                'ol/UWEoCjColRqcSPX0o9s9v4bJmzy+M',
                'doctrine_encryption',
            ],
            [
                '0+/bKg0l4OUIW5hMFgm9uqe1aChUQ2ew',
                '1234567890',
            ],
            [
                'IlTvWisccthAKZu+KnyFncOFToFC7RMz',
                'string_123456',
            ],
        ];
    }
}
