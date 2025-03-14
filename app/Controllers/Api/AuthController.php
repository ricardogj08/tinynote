<?php

namespace App\Controllers\Api;

use App\Models\UserModel;
use App\Utils\Env;
use App\Utils\Jwt;
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
            'email' => v::stringType()->notEmpty()->email()->length(4, 255, true),
            'username' => v::stringType()->notEmpty()->alnum()->length(4, 32, true),
            'password' => v::stringType()->notEmpty()->graph()->length(8, 64, true)
        ];
    }

    /*
     * Inicia la sesión de un usuario.
     */
    public function login($req, $res)
    {
        $data = [];

        // Obtiene los campos del cuerpo de la petición.
        foreach (['nickname', 'password'] as $field) {
            if (v::key($field, v::notOptional(), true)->validate($req->body)) {
                $data[$field] = $req->body[$field];
            }
        }

        $rules = $this->getValidationRules();

        $identifyBy = 'username';

        // Comprueba el modo de autenticación.
        if (v::key('nickname', $rules['email'], true)->validate($data)) {
            $identifyBy = 'email';
            $data['nickname'] = strtolower($data['nickname']);
        }

        // Comprueba los campos del cuerpo de la petición.
        try {
            v::key('nickname', $rules[$identifyBy], true)
                ->key('password', $rules['password'], true)
                ->assert($data);
        } catch (NestedValidationException $e) {
            $res->status(StatusCode::BAD_REQUEST)->json([
                'validations' => $e->getMessages()
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
                'error' => 'Access credentials are invalid'
            ]);
        }

        // Genera el token de autenticación.
        $token = (new Jwt())->encode([
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

    /*
     * Consulta la información del usuario autenticado.
     */
    public function me($req, $res)
    {
        $userAuth = $req->app->local('userAuth');

        // Consulta la información del usuario autenticado.
        $userAuth = UserModel::factory()
            ->select('id, username, email, active, is_admin, created_at, updated_at')
            ->find($userAuth['id']);

        $res->json([
            'data' => $userAuth
        ]);
    }
}
