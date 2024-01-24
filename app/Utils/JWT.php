<?php

namespace App\Utils;

use Firebase\JWT\JWT as FJWT;
use Firebase\JWT\Key;
use Exception;

class JWT
{
    private $configFile = 'jwt';
    private $privateKey;
    private $publicKey;
    private $algo = 'RS256';

    public function __construct()
    {
        $this->mount();
    }

    private function mount()
    {
        $config = Config::getFromFile($this->configFile);

        $this->privateKey = file_get_contents($config['privateKey']);
        $this->publicKey = file_get_contents($config['publicKey']);

        if ($this->privateKey === false || $this->publicKey === false) {
            throw new Exception('JWT private or public key file not found.');
        }
    }

    public function encode(array $payload)
    {
        return FJWT::encode($payload, $this->privateKey, $this->algo);
    }

    public function decode(string $jwt)
    {
        return FJWT::decode($jwt, new Key($this->publicKey, $this->algo));
    }
}
