<?php

namespace App\Controllers\Api;

use App\Models\UserModel;
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
            'password' => v::optional(v::stringType()->notEmpty()->graph()->length(8, 64, true)),
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
        foreach ($rules as $field => $rule) {
            $data[$field] = $req->body[$field] ?? null;
        }

        // Comprueba los campos del cuerpo de la petición.
        try {
            v::key('email', $rules['email'], true)
                ->key('username', $rules['username'], true)
                ->key('password', $rules['password'], false)
                ->keyValue('pass_confirm', $rules['pass_confirm'], 'password')
                ->assert($data);
        } catch (NestedValidationException $e) {
            $res->status(StatusCode::BAD_REQUEST)->json([
                'validations' => $e->getMessages()
            ]);
        }

        $userAuth = $req->app->local('userAuth');

        // Consulta la información modificada del usuario.
        $userAuth = UserModel::factory()
            ->select('id, username, email, active, is_admin, created_at, updated_at')
            ->find($userAuth['id']);

        $res->json([
            'data' => $userAuth
        ]);
    }
}
