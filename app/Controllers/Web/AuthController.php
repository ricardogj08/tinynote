<?php

namespace App\Controllers\Web;

use App\Utils\Api;
use App\Utils\Url;
use PH7\JustHttp\StatusCode;

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
     * Obtiene las opciones de configuración
     * de la cookie de autenticación.
     */
    private function getCookieOptions()
    {
        return [
            'path' => '/',
            'httpOnly' => true
        ];
    }

    /*
     * Renderiza el formulario de inicio de sesión.
     */
    public function loginView($req, $res)
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

        $res->render('auth/login', [
            'app' => $req->app,
            'values' => $values,
            'validations' => $validations,
            'error' => $error
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

            // Envía los mensajes de validación de los campos del formulario.
            if (!empty($body['validations'])) {
                $req->session['validations'] = $body['validations'];
            }

            // Envía el mensaje de error de la petición.
            $req->session['error'] = $body['error'] ?? 'Could not login';

            $res->redirect(Url::build('login'), StatusCode::FOUND);
        }

        $cookieOptions = $this->getCookieOptions();

        // 24 horas
        $cookieOptions['expire'] = strtotime('tomorrow');

        // Genera la cookie de autenticación del usuario.
        $res->cookie('userAuth', $token, $cookieOptions);

        $res->redirect(Url::build('notes'), StatusCode::FOUND);
    }

    /*
     * Cierra la sesión de un usuario.
     */
    public function logout($req, $res)
    {
        $res->clearCookie('userAuth', $this->getCookieOptions());

        $res->redirect(Url::build('login'), StatusCode::FOUND);
    }
}
