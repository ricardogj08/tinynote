<?php

namespace App\Controllers\Api;

use App\Models\UserModel;
use App\Utils\Crypt;
use App\Utils\DB;
use App\Utils\Files;
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
            $data[$key] = (int) v::key($key, v::notOptional()->trueVal(), true)->validate($data);
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
    public function index($req, $res)
    {
        $userModel = UserModel::factory();

        // Consulta la información de los usuarios registrados.
        $users = $userModel
            ->select('users.id, users.username, users.email, users.active, users.is_admin, COUNT(notes.id) as number_notes, COUNT(tags.id) as number_tags, users.created_at, users.updated_at')
            ->notes()
            ->tags()
            ->groupBy('users.id')
            ->orderBy('users.updated_at DESC')
            ->get();

        $res->json([
            'data' => $users
        ]);
    }

    /*
     * Consulta la información de un usuario.
     */
    public function show($req, $res)
    {
        $params = $req->params;

        $rules = $this->getValidationRules();

        // Comprueba los parámetros de la ruta.
        try {
            v::key('uuid', $rules['id'], true)->assert($params);
        } catch (NestedValidationException $e) {
            $res->status(StatusCode::BAD_REQUEST)->json([
                'error' => $e->getMessage()
            ]);
        }

        // Consulta la información del usuario.
        $user = UserModel::factory()
            ->select('id, username, email, active, is_admin, created_at, updated_at')
            ->find($params['uuid']);

        // Comprueba que el usuario se encuentra registrado.
        if (empty($user)) {
            $res->status(StatusCode::NOT_FOUND)->json([
                'error' => 'User cannot be found'
            ]);
        }

        $res->json([
            'data' => $user
        ]);
    }

    /*
     * Modifica o actualiza la información de un usuario.
     */
    public function update($req, $res)
    {
        $params = $req->params;

        $rules = $this->getValidationRules();

        // Comprueba los parámetros de la ruta.
        try {
            v::key('uuid', $rules['id'], true)->assert($params);
        } catch (NestedValidationException $e) {
            $res->status(StatusCode::BAD_REQUEST)->json([
                'error' => $e->getMessage()
            ]);
        }

        $userModel = UserModel::factory();

        // Consulta la información del usuario que será modificado.
        $user = $userModel
            ->select('id')
            ->find($params['uuid']);

        // Comprueba que el usuario se encuentra registrado.
        if (empty($user)) {
            $res->status(StatusCode::NOT_FOUND)->json([
                'error' => 'User cannot be found'
            ]);
        }

        $data = [];

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
            v::key('username', $rules['username'], false)
                ->key('email', $rules['email'], false)
                ->key('password', $rules['password'], false)
                ->key('active', $rules['active'], false)
                ->key('is_admin', $rules['is_admin'], false)
                ->assert($data);

            /*
             * Comprueba la confirmación de la contraseña
             * si la contraseña se encuentra presente.
             */
            if (v::key('password', v::notOptional(), true)->validate($data)) {
                v::keyValue('pass_confirm', $rules['pass_confirm'], 'password')
                    ->assert($data);

                // Encripta la nueva contraseña del usuario.
                $data['password'] = Password::encrypt($data['password']);

                unset($data['pass_confirm']);
            }
        } catch (NestedValidationException $e) {
            $res->status(StatusCode::BAD_REQUEST)->json([
                'validations' => $e->getMessages()
            ]);
        }

        /*
         * Comprueba que el nombre del usuario
         * sea único solo si se encuentra presente.
         */
        if (v::key(('username'), v::notOptional(), true)->validate($data)) {
            $existsUsername = $userModel
                ->reset()
                ->select('id')
                ->where('username', $data['username'])
                ->where('id', '!=', $user['id'])
                ->value('id');

            if (!empty($existsUsername)) {
                $res->status(StatusCode::CONFLICT)->json([
                    'error' => 'A user already exists with that username'
                ]);
            }
        }

        /*
         * Comprueba que el email del usuario
         * sea único solo si se encuentra presente.
         */
        if (v::key('email', v::notOptional(), true)->validate($data)) {
            $data['email'] = mb_strtolower($data['email']);

            $existsEmail = $userModel
                ->reset()
                ->select('id')
                ->where('email', $data['email'])
                ->where('id', '!=', $user['id'])
                ->value('id');

            if (!empty($existsEmail)) {
                $res->status(StatusCode::CONFLICT)->json([
                    'error' => 'A user already exists with that email'
                ]);
            }
        }

        // Establece el rol y el estatus del usuario si se encuentran presentes.
        foreach (['is_admin', 'active'] as $key) {
            if (v::key($key, v::notOptional(), true)->validate($data)) {
                $data[$key] = (int) v::key($key, v::trueVal())->validate($data);
            }
        }

        // Modifica total o parcialmente la información del usuario.
        if (!empty($data)) {
            $data['updated_at'] = DB::datetime();

            $userModel
                ->reset()
                ->where('id', $user['id'])
                ->update($data);
        }

        // Consulta la información modificada del usuario.
        $updatedUser = $userModel
            ->reset()
            ->select('id, username, email, active, is_admin, created_at, updated_at')
            ->find($user['id']);

        $res->json([
            'data' => $updatedUser
        ]);
    }

    /*
     * Elimina un usuario.
     */
    public function delete($req, $res)
    {
        $params = $req->params;

        $rules = $this->getValidationRules();

        // Comprueba los parámetros de la ruta.
        try {
            v::key('uuid', $rules['id'], true)->assert($params);
        } catch (NestedValidationException $e) {
            $res->status(StatusCode::BAD_REQUEST)->json([
                'error' => $e->getMessage()
            ]);
        }

        $userAuth = $req->app->local('userAuth');

        $userModel = UserModel::factory();

        // Consulta la información del usuario que será eliminado.
        $deletedUser = $userModel
            ->select('users.id, users.username, users.email, users.active, users.is_admin, COUNT(notes.id) as number_notes, COUNT(tags.id) as number_tags, users.created_at, users.updated_at')
            ->notes()
            ->tags()
            ->where('users.id', '!=', $userAuth['id'])
            ->groupBy('users.id')
            ->find($params['uuid']);

        // Comprueba que el usuario se encuentra registrado.
        if (empty($deletedUser)) {
            $res->status(StatusCode::NOT_FOUND)->json([
                'error' => 'User cannot be found'
            ]);
        }

        $crypt = new Crypt($deletedUser['id']);

        // Elimina el directorio de las llaves de cifrado del usuario.
        Files::rrmdir($crypt->getUserPathKeys());

        // Elimina la información del usuario.
        $userModel
            ->reset()
            ->where('id', $deletedUser['id'])
            ->delete();

        $res->json([
            'data' => $deletedUser
        ]);
    }
}
