<?php

namespace App\Utils;

use App\Utils\Config;
use Spatie\Crypto\Rsa\KeyPair;
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;
use Exception;

class Crypt
{
    private const configFilename = 'crypt';
    private const privateKeyFilename = 'private.key';
    private const publicKeyFilename = 'public.key';
    private const privateKeyBits = 4096;

    private $privateKey;
    private $publicKey;

    public function __construct(string $uuid)
    {
        $this->mount($uuid);
    }

    private function mount(string $uuid)
    {
        $config = Config::getFromFilename(self::configFilename);

        $pathKeys = $config['pathKeys'];

        if (!is_dir($pathKeys)) {
            throw new Exception(sprintf('Crypt path keys "%s" cannot be found.', $pathKeys));
        }

        $userPathKeys = sprintf('%s/%s/', realpath($pathKeys), $uuid);

        // Crea el directorio de las llaves de cifrado del usuario.
        is_dir($userPathKeys) || mkdir($userPathKeys);

        $pathToPrivateKey = $userPathKeys . self::privateKeyFilename;
        $pathToPublicKey = $userPathKeys . self::publicKeyFilename;

        // Genera las llaves de cifrado si no existen.
        if (!is_file($pathToPrivateKey) && !is_file($pathToPublicKey)) {
            (new KeyPair())->generate($pathToPrivateKey, $pathToPublicKey);
        }

        $this->privateKey = PrivateKey::fromFile($pathToPrivateKey);
        $this->publicKey = PublicKey::fromFile($pathToPublicKey);
    }

    /*
     * Encripta un string.
     */
    public function encrypt(string $data)
    {
        $encryptedData = '';

        foreach (str_split($data, 117) as $part) {
            $encryptedData .= base64_encode($this->publicKey->encrypt($part));
        }

        return $encryptedData;
    }

    /*
     * Desencripta un string.
     */
    public function decrypt(string $encryptedData)
    {
        $data = '';

        foreach (str_split($encryptedData, 128) as $part) {
            $data .= $this->privateKey->decrypt(base64_decode($part));
        }

        return $data;
    }
}
