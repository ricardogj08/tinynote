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
        $data = [];

        // Obtiene los valores de los campos del formulario.
        foreach ($this->getFormFields() as $field) {
            $data[$field] = $req->session['data'][$field] ?? null;
        }

        $res->render('auth/login', [
            'app' => $req->app,
            'data' => $data
        ]);
    }

    /*
     * Inicia la sesión de un usuario.
     */
    public function loginAction($req, $res)
    {
        $data = $req->body;

        $client = Api::client();

        $response = $client->post('v1/auth/login');

        // Comprueba el cuerpo de la petición.
        if (empty($response->success)) {
            foreach ($this->getFormFields() as $field) {
                $req->session['data'][$field] = $data[$field] ?? null;
            }

            $res->redirect(Url::build('login'));
        }
    }
}
