<?php

namespace App\Utils;

use App\Utils\Config;
use Spatie\Crypto\Rsa\KeyPair;
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;
use RuntimeException;

class Crypt
{
    private const CONFIG_FILENAME = 'crypt';
    private const PRIVATE_KEY_FILENAME = 'private.key';
    private const PUBLIC_KEY_FILENAME = 'public.key';
    private const PRIVATE_KEY_BITS = 4096;

    private $userPathKeys;
    private $privateKey;
    private $publicKey;

    public function __construct(string $uuid)
    {
        $this->mount($uuid);
    }

    private function mount(string $uuid)
    {
        $config = Config::getFromFilename(self::CONFIG_FILENAME);

        $pathKeys = $config['path_keys'];

        if (!is_dir($pathKeys)) {
            throw new RuntimeException(sprintf('Crypt path keys "%s" cannot be found.', $pathKeys));
        }

        $this->userPathKeys = sprintf('%s/%s/', realpath($pathKeys), $uuid);

        // Crea el directorio de las llaves de cifrado del usuario.
        is_dir($this->userPathKeys) || mkdir($this->userPathKeys);

        $pathToPrivateKey = $this->userPathKeys . self::PRIVATE_KEY_FILENAME;
        $pathToPublicKey = $this->userPathKeys . self::PUBLIC_KEY_FILENAME;

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

        foreach (str_split($data, self::PRIVATE_KEY_BITS / 8 - 64) as $part) {
            $encryptedData .= $this->publicKey->encrypt($part);
        }

        return base64_encode($encryptedData);
    }

    /*
     * Desencripta un string.
     */
    public function decrypt(string $encryptedData)
    {
        $data = '';

        foreach (str_split(base64_decode($encryptedData), self::PRIVATE_KEY_BITS * 128 / 1024) as $part) {
            $data .= $this->privateKey->decrypt($part);
        }

        return $data;
    }

    /*
     * Obtiene el directorio de las llaves de cifrado del usuario.
     */
    public function getUserPathKeys()
    {
        return $this->userPathKeys;
    }
}
