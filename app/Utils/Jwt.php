<?php

namespace App\Utils;

use App\Utils\Config;
use Firebase\JWT\JWT as FirebaseJwt;
use Firebase\JWT\Key as FirebaseKey;
use Exception;

class Jwt
{
    private const CONFIG_FILENAME = 'jwt';
    private const ALGORITHM = 'RS256';

    private $privateKey;
    private $publicKey;

    public function __construct()
    {
        $this->mount();
    }

    private function mount()
    {
        $config = Config::getFromFilename(self::CONFIG_FILENAME);

        $this->privateKey = file_get_contents($config['private_key_path']);
        $this->publicKey = file_get_contents($config['public_key_path']);

        if ($this->privateKey === false) {
            throw new Exception(sprintf('JWT private key file "%s" cannot be found.', $config['privateKeyPath']));
        }

        if ($this->publicKey === false) {
            throw new Exception(sprintf('JWT public key file "%s" cannot be found.', $config['publicKeyPath']));
        }
    }

    /*
     * Genera un token JWT.
     */
    public function encode(array $payload)
    {
        return FirebaseJwt::encode($payload, $this->privateKey, self::ALGORITHM);
    }

    /*
     * Decodifica un token JWT.
     */
    public function decode(string $jwt)
    {
        return FirebaseJwt::decode($jwt, new FirebaseKey($this->publicKey, self::ALGORITHM));
    }
}
