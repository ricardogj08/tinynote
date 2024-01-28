<?php

namespace App\Controllers\Api;

use App\Models\UserModel;
use App\Utils\Env;
use App\Utils\JWT;
use App\Utils\Password;
use PH7\JustHttp\StatusCode;
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
            'email' => v::stringType()->email()->length(null, 255, true),
            'username' => v::stringType()->alnum()->length(4, 32, true),
            'password' => v::stringType()->graph()->length(8, 64, true)
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
            $res->status(StatusCode::BAD_REQUEST)->json([
                'errors' => $e->getMessages()
            ]);
        }

        // Consulta la información del usuario que intenta autenticarse.
        $userAuth = UserModel::factory()
            ->select('id, password, active')
            ->where($identifyBy, $data['nickname'])
            ->where('active', true)
            ->first();

        // Comprueba la contraseña del usuario.
        if (empty($userAuth) || !Password::verify($data['password'], $userAuth['password'])) {
            $res->status(StatusCode::UNAUTHORIZED)->json([
                'errors' => 'Access credentials are invalid'
            ]);
        }

        // Genera el token de autenticación.
        $token = (new JWT())->encode([
            'iss' => Env::get('APP_NAME'),
            'sub' => $userAuth['id'],
            'aud' => Env::get('APP_URL'),
            'exp' => strtotime('tomorrow'),  // 24 horas
            'iat' => strtotime('now')
        ]);

        $res->json([
            'data' => [
                'token' => $token
            ]
        ]);
    }
}
