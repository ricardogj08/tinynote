<?php

namespace App\Controllers\Api;

use App\Models\UserModel;
use App\Utils\DB;
use App\Utils\Password;
use PH7\JustHttp\StatusCode;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class ProfileController
{
    /*
     * Obtiene las reglas de validación.
     */
    private function getValidationRules()
    {
        return [
            'email' => v::stringType()->notEmpty()->email()->length(4, 255, true),
            'username' => v::stringType()->notEmpty()->alnum()->length(4, 32, true),
            'password' => v::stringType()->notEmpty()->graph()->length(8, 64, true),
            'pass_confirm' => 'equals'
        ];
    }

    /*
     * Modifica o actualiza el perfil
     * del usuario autenticado.
     */
    public function update($req, $res)
    {
        $data = [];

        $rules = $this->getValidationRules();

        // Obtiene los campos del cuerpo de la petición.
        foreach (array_keys($rules) as $field) {
            if (v::key($field, v::notOptional(), true)->validate($req->body)) {
                $data[$field] = $req->body[$field];
            }
        }

        // Comprueba los campos del cuerpo de la petición.
        try {
            v::key('email', $rules['email'], false)
                ->key('username', $rules['username'], false)
                ->key('password', $rules['password'], false)
                ->assert($data);

            /*
             * Comprueba la confirmación de la contraseña
             * si la contraseña se encuentra presente.
             */
            if (v::key('password', v::notOptional(), true)->validate($data)) {
                v::keyValue('pass_confirm', $rules['pass_confirm'], 'password')
                    ->assert($data);

                // Encripta la nueva contraseña del usuario autenticado.
                $data['password'] = Password::encrypt($data['password']);

                unset($data['pass_confirm']);
            }
        } catch (NestedValidationException $e) {
            $res->status(StatusCode::BAD_REQUEST)->json([
                'validations' => $e->getMessages()
            ]);
        }

        $userAuth = $req->app->local('userAuth');

        $userModel = UserModel::factory();

        /*
         * Comprueba que el email del usuario autenticado
         * sea único solo si se encuentra presente.
         */
        if (v::key('email', v::notOptional(), true)->validate($data)) {
            $data['email'] = mb_strtolower($data['email']);

            $existsEmail = $userModel
                ->reset()
                ->select('id')
                ->where('email', $data['email'])
                ->where('id', '!=', $userAuth['id'])
                ->value('id');

            if (!empty($existsEmail)) {
                $res->status(StatusCode::CONFLICT)->json([
                    'error' => 'A user already exists with that email'
                ]);
            }
        }

        /*
         * Comprueba que el nombre del usuario autenticado
         * sea único solo si se encuentra presente.
         */
        if (v::key(('username'), v::notOptional(), true)->validate($data)) {
            $existsUsername = $userModel
                ->reset()
                ->select('id')
                ->where('username', $data['username'])
                ->where('id', '!=', $userAuth['id'])
                ->value('id');

            if (!empty($existsUsername)) {
                $res->status(StatusCode::CONFLICT)->json([
                    'error' => 'A user already exists with that username'
                ]);
            }
        }

        // Modifica total o parcialmente la información del usuario autenticado.
        if (!empty($data)) {
            $data['updated_at'] = DB::datetime();

            $userModel
                ->reset()
                ->where('id', $userAuth['id'])
                ->update($data);
        }

        // Consulta la información modificada del usuario autenticado.
        $updatedUserAuth = $userModel
            ->reset()
            ->select('id, username, email, active, is_admin, created_at, updated_at')
            ->find($userAuth['id']);

        $res->json([
            'data' => $updatedUserAuth
        ]);
    }
}
