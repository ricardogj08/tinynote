<?php

namespace App\Controllers\Web;

use App\Utils\Api;
use App\Utils\Url;

class AuthController
{
    /*
     * Obtiene los nombres de los campos del formulario.
     */
    private function getFormFields()
    {
        return ['nickname', 'password'];
    }

    /*
     * Renderiza el formulario de inicio de sesión.
     */
    public function loginView($req, $res)
    {
        $values = [];
        $validations = [];
        $errors = $req->session['errors'] ?? null;

        /*
         * Obtiene los valores y errores de validación
         * de los campos del formulario.
         */
        foreach ($this->getFormFields() as $field) {
            $values[$field] = $req->session['values'][$field] ?? null;
            $validations[$field] = $errors[$field] ?? null;
        }

        unset($req->session['values'], $req->session['errors']);

        $res->render('auth/login', [
            'app' => $req->app,
            'values' => $values,
            'validations' => $validations,
            'errors' => $errors
        ]);
    }

    /*
     * Inicia la sesión de un usuario.
     */
    public function loginAction($req, $res)
    {
        $data = [];

        // Obtiene los valores de los campos del formulario.
        foreach ($this->getFormFields() as $field) {
            $data[$field] = $req->body[$field] ?? null;
        }

        $client = Api::client();

        // Realiza la petición de inicio de sesión del usuario.
        $response = $client->post('v1/auth/login', [], $data);

        $body = json_decode($response->body ?? '', true);

        $token = $body['data']['token'] ?? null;

        // Comprueba el cuerpo de la petición.
        if (empty($response->success) || empty($token)) {
            $req->session['values'] = $data;

            // Envía los errores de los campos del formulario.
            if (!empty($body['errors'])) {
                $req->session['errors'] = $body['errors'];
            }

            $res->redirect(Url::build('login'));
        }

        // Genera la cookie de autenticación del usuario.
        $res->cookie('userAuth', $token);

        $res->redirect(Url::build('notes'));
    }
}
