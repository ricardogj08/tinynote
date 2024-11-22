<?php

namespace App\Controllers\Web;

use App\Utils\Api;
use App\Utils\Url;
use PH7\JustHttp\StatusCode;

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
        foreach (['success', 'error'] as $key) {
            unset($req->session[$key]);
        }

        $res->render('users/index', [
            'app' => $req->app,
        ]);
    }

    /*
     * Renderiza el formulario de modificación de usuarios.
     */
    public function edit($req, $res)
    {
        $res->render('users/edit', [
            'app' => $req->app,
        ]);
    }

    /*
     * Modifica la información de un usuario.
     */
    public function update($req, $res) {}

    /*
     * Elimina un usuario.
     */
    public function delete($req, $res) {}
}
