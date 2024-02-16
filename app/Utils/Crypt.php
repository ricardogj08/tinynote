<?php

namespace App\Utils;

use Spatie\Crypto\Rsa\KeyPair;
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;
use Exception;

class Crypt
{
    private $configFile = 'crypt';
    private $privateKeyFile = 'private.key';
    private $publicKeyFile = 'public.key';
    private $privateKey;
    private $publicKey;

    public function __construct(string $subdirectory)
    {
        $this->mount($subdirectory);
    }

    private function mount(string $subdirectory)
    {
        $config = Config::getFromFile($this->configFile);

        $keysDirectory = $config['keys-directory'];

        if (!is_dir($keysDirectory)) {
            throw new Exception("Crypt keys directory '{$keysDirectory}' cannot be found.");
        }

        $keysPath = realpath($keysDirectory) . "/{$subdirectory}/";

        // Crea el subdirectorio de las llaves de cifrado si no existe.
        is_dir($keysPath) || mkdir($keysPath);

        $privateKeyPath = $keysPath . $this->privateKeyFile;
        $publicKeyPath = $keysPath . $this->publicKeyFile;

        // Genera las llaves de cifrado si no existen.
        if (!is_file($privateKeyPath) && !is_file($publicKeyPath)) {
            (new KeyPair())->generate($privateKeyPath, $publicKeyPath);
        }

        $this->privateKey = PrivateKey::fromFile($privateKeyPath);
        $this->publicKey = PublicKey::fromFile($publicKeyPath);
    }

    /*
     * Encripta un string.
     */
    public function encrypt(string $data)
    {
        return base64_encode($this->publicKey->encrypt($data));
    }

    /*
     * Desencripta un string.
     */
    public function decrypt(string $encryptedData)
    {
        return $this->privateKey->decrypt(base64_decode($encryptedData));
    }

    /*
     * Comprueba si puede desencriptar un string.
     */
    public function canDecrypt(string $encryptedData)
    {
        return $this->privateKey->decrypt->canDecrypt(base64_decode($encryptedData));
    }
}
