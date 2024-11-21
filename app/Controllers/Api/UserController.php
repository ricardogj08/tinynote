<?php

namespace App\Controllers\Api;

use App\Models\UserModel;
use App\Utils\DB;
use App\Utils\Password;
use PH7\JustHttp\StatusCode;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class UserController
{
    /*
     * Obtiene las reglas de validación.
     */
    private function getValidationRules()
    {
        return [
            'id' => v::stringType()->NotEmpty()->Uuid(),
            'username' => v::stringType()->notEmpty()->alnum()->length(4, 32, true),
            'email' => v::stringType()->notEmpty()->email()->length(4, 255, true),
            'password' => v::stringType()->notEmpty()->graph()->length(8, 64, true),
            'active' => v::boolVal(),
            'is_admin' => v::boolVal(),
            'pass_confirm' => 'equals'
        ];
    }

    /*
     * Registra un nuevo usuario.
     */
    public function create($req, $res)
    {
        $data = [];

        $rules = $this->getValidationRules();

        // Selecciona solo los campos necesarios.
        $fields = array_diff(array_keys($rules), ['id']);

        // Obtiene los campos del cuerpo de la petición.
        foreach ($fields as $field) {
            if (v::key($field, v::notOptional(), true)->validate($req->body)) {
                $data[$field] = $req->body[$field];
            }
        }

        // Comprueba los campos del cuerpo de la petición.
        try {
            v::key('username', $rules['username'], true)
                ->key('email', $rules['email'], true)
                ->key('password', $rules['password'], true)
                ->key('active', $rules['active'], false)
                ->key('is_admin', $rules['is_admin'], false)
                ->keyValue('pass_confirm', $rules['pass_confirm'], 'password')
                ->assert($data);
        } catch (NestedValidationException $e) {
            $res->status(StatusCode::BAD_REQUEST)->json([
                'validations' => $e->getMessages()
            ]);
        }

        // Convierta a minúsculas el email del usuario.
        $data['email'] = mb_strtolower($data['email']);

        $userModel = UserModel::factory();

        $existsNewUser = $userModel
            ->select('id')
            ->where('(username = :username OR email = :email)')
            ->param(':username', $data['username'])
            ->param(':email', $data['email'])
            ->value('id');

        // Comprueba que el usuario sea único.
        if (!empty($existsNewUser)) {
            $res->status(StatusCode::CONFLICT)->json([
                'error' => 'A user already exists with that email or username'
            ]);
        }

        unset($data['pass_confirm']);

        // Genera el UUID del nuevo usuario.
        $data['id'] = DB::generateUuid();

        // Encripta la contraseña del nuevo usuario.
        $data['password'] = Password::encrypt($data['password']);

        // Establece el rol y el estatus del nuevo usuario.
        foreach (['is_admin', 'active'] as $key) {
            $data[$key] = v::key($key, v::notOptional()->trueVal(), true)->validate($data);
        }

        $data['created_at'] = $data['updated_at'] = DB::datetime();

        // Registra la información del nuevo usuario.
        $userModel->reset()->insert($data);

        // Consulta la información del usuario registrado.
        $newUser = $userModel
            ->reset()
            ->select('id, username, email, active, is_admin, created_at, updated_at')
            ->find($data['id']);

        $res->status(StatusCode::CREATED)->json([
            'data' => $newUser
        ]);
    }

    /*
     * Consulta los usuarios registrados.
     */
    public function index($req, $res) {}

    /*
     * Modifica o actualiza la información de un usuario.
     */
    public function update($req, $res) {}

    /*
     * Elimina un usuario.
     */
    public function delete($req, $res) {}
}
