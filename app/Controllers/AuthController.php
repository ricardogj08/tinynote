<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Utils\Env;
use App\Utils\JWT;
use App\Utils\Password;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class AuthController
{
    /*
     * Obtiene las reglas de validación.
     */
    private function getValidationRules()
    {
        return [
            'email' => v::email()->length(null, 255, true),
            'username' => v::alnum()->length(4, 32, true),
            'password' => v::graph()->length(8, 64, true)
        ];
    }

    /*
     * Inicia la sesión de un usuario.
     */
    public function login($req, $res)
    {
        $rules = $this->getValidationRules();

        $data = $req->body;

        $identifyBy = 'username';

        // Comprueba el modo de autenticación.
        if (v::key('nickname', $rules['email'], true)->validate($data)) {
            $identifyBy = 'email';
            $data['nickname'] = mb_strtolower($data['nickname']);
        }

        // Comprueba los campos del cuerpo de la petición.
        try {
            v::key('nickname', $rules[$identifyBy], true)
                ->key('password', $rules['password'], true)
                ->assert($data);
        } catch (NestedValidationException $e) {
            $res->json([
                'errors' => $e->getMessages()
            ]);
        }

        $userModel = UserModel::factory();

        // Consulta la información del usuario que intenta autenticarse.
        $user = $userModel
            ->select('id, password, active')
            ->where($identifyBy, $data['nickname'])
            ->where('active', true)
            ->first();

        // Comprueba la contraseña del usuario.
        if (empty($user) || !Password::verify($data['password'], $user['password'])) {
            $res->json([
                'errors' => 'Access credentials are invalid'
            ]);
        }

        $jwt = new JWT();

        // Genera el token de autenticación.
        $token = $jwt->encode([
            'iss' => Env::get('APP_NAME'),
            'sub' => $user['id'],
            'aud' => Env::get('APP_URL'),
            'exp' => strtotime('now'),
            'iat' => strtotime('tomorrow')
        ]);

        $res->json([
            'data' => [
                'token' => $token
            ]
        ]);
    }
}
