<?php

namespace App\Controllers\Web;

use App\Utils\Api;
use App\Utils\Url;
use PH7\JustHttp\StatusCode;
use Respect\Validation\Validator as v;

class UserController
{
    /*
     * Obtiene los nombres de los campos del formulario.
     */
    private function getFormFields()
    {
        return [
            'username',
            'email',
            'password',
            'active',
            'is_admin',
            'pass_confirm'
        ];
    }

    /*
     * Renderiza el formulario de registro de usuarios.
     */
    public function new($req, $res)
    {
        $values = [];
        $validations = [];
        $error = $req->session['error'] ?? null;

        /*
         * Obtiene los valores y los mensajes de validación
         * de los campos del formulario.
         */
        foreach ($this->getFormFields() as $field) {
            $values[$field] = $req->session['values'][$field] ?? null;
            $validations[$field] = $req->session['validations'][$field] ?? null;
        }

        foreach (['values', 'validations', 'error'] as $key) {
            unset($req->session[$key]);
        }

        $res->render('users/new', [
            'app' => $req->app,
            'values' => $values,
            'validations' => $validations,
            'error' => $error
        ]);
    }

    /*
     * Registra un nuevo usuario.
     */
    public function create($req, $res)
    {
        $data = [];

        // Obtiene los valores de los campos del formulario.
        foreach ($this->getFormFields() as $field) {
            $data[$field] = $req->body[$field] ?? null;
        }

        $client = Api::client();

        // Realiza la petición de registro del usuario.
        $response = $client->post('v1/users', [], $data);

        $body = json_decode($response->body ?? '', true);

        // Comprueba el cuerpo de la petición.
        if (empty($response->success) || empty($body['data'])) {
            $req->session['values'] = $data;

            // Envía los mensajes de validación de los campos del formulario.
            if (!empty($body['validations'])) {
                $req->session['validations'] = $body['validations'];
            }

            // Envía el mensaje de error de la petición..
            $req->session['error'] = $body['error'] ?? 'The user could not be created';

            $res->redirect(Url::build('users/new'), StatusCode::FOUND);
        }

        $req->session['success'] = 'The user was created correctly';

        $res->redirect(Url::build('users'), StatusCode::FOUND);
    }

    /*
     * Consulta los usuarios registrados.
     */
    public function index($req, $res)
    {
        $client = Api::client();

        // Realiza la petición de consulta de los usuarios registrados.
        $response = $client->get('v1/users');

        $body = json_decode($response->body ?? '', true);

        $users = $body['data'] ?? [];

        $error = $body['error'] ?? $req->session['error'] ?? null;

        $success = $req->session['success'] ?? null;

        foreach (['success', 'error'] as $key) {
            unset($req->session[$key]);
        }

        $res->render('users/index', [
            'app' => $req->app,
            'users' => $users,
            'success' => $success,
            'error' => $error
        ]);
    }

    /*
     * Renderiza el formulario de modificación de usuarios.
     */
    public function edit($req, $res)
    {
        $uuid = $req->params['uuid'] ?? '';

        $client = Api::client();

        /*
         * Realiza la petición de consulta
         * de la información del usuario.
         */
        $response = $client->get('v1/users/' . $uuid);

        $body = json_decode($response->body ?? '', true);

        $user = $body['data'] ?? [];

        // Comprueba el cuerpo de la petición.
        if (empty($response->success) || empty($user)) {
            // Envía el mensaje de error de la petición.
            $req->session['error'] = $body['error'] ?? 'The user could not be edited';

            $res->redirect(Url::build('users'), StatusCode::FOUND);
        }

        $validations = [];
        $error = $req->session['error'] ?? null;
        $success = $req->session['success'] ?? null;

        /*
         * Obtiene los valores y los mensajes de validación
         * de los campos del formulario.
         */
        foreach ($this->getFormFields() as $field) {
            $validations[$field] = $req->session['validations'][$field] ?? null;
        }

        foreach (['validations', 'error', 'success'] as $key) {
            unset($req->session[$key]);
        }

        $res->render('users/edit', [
            'app' => $req->app,
            'user' => $user,
            'validations' => $validations,
            'error' => $error,
            'success' => $success
        ]);
    }

    /*
     * Modifica la información de un usuario.
     */
    public function update($req, $res)
    {
        $uuid = $req->params['uuid'] ?? '';

        $data = [];

        // Obtiene los valores de los campos del formulario.
        foreach ($this->getFormFields() as $field) {
            $data[$field] = $req->body[$field] ?? null;
        }

        $client = Api::client();

        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];

        // Establece el rol y el estatus del usuario si no encuentran presentes.
        foreach (['active', 'is_admin'] as $key) {
            if (v::key($key, v::optional(v::falseVal()), false)->validate($data)) {
                $data[$key] = (string) (int) false;
            }
        }

        // Realiza la petición de modificación del usuario.
        $response = $client->put('v1/users/' . $uuid, $headers, $data);

        $body = json_decode($response->body ?? '', true);

        // Comprueba el cuerpo de la petición.
        if (empty($response->success) || empty($body['data'])) {
            // Envía los mensajes de validación de los campos del formulario.
            if (!empty($body['validations'])) {
                $req->session['validations'] = $body['validations'];
            }

            // Envía el mensaje de error de la petición.
            $req->session['error'] = $body['error'] ?? 'The user could not be updated';

            $res->redirect(Url::build('users/edit/' . $uuid), StatusCode::FOUND);
        }

        $req->session['success'] = 'The user was modified correctly';

        $res->redirect(Url::build('users/edit/' . $uuid), StatusCode::FOUND);
    }

    /*
     * Elimina un usuario.
     */
    public function delete($req, $res)
    {
        $uuid = $req->params['uuid'] ?? '';

        $client = Api::client();

        // Realiza la petición de eliminación del usuario.
        $response = $client->delete('v1/users/' . $uuid);

        $body = json_decode($response->body ?? '', true);

        // Comprueba el cuerpo de la petición.
        if (empty($response->success) || empty($body['data'])) {
            // Envía el mensaje de error de la petición.
            $req->session['error'] = $body['error'] ?? 'The user could not be deleted';

            $res->redirect(Url::build('users'), StatusCode::FOUND);
        }

        $req->session['success'] = 'The user was deleted correctly';

        $res->redirect(Url::build('users'), StatusCode::FOUND);
    }
}
