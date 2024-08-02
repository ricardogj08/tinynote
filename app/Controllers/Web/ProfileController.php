<?php

namespace App\Controllers\Web;

use App\Utils\Api;
use App\Utils\Url;
use PH7\JustHttp\StatusCode;

class ProfileController
{
    /*
     * Obtiene los nombres de los campos del formulario.
     */
    private function getFormFields()
    {
        return ['email', 'username', 'password', 'pass_confirm'];
    }

    /*
     * Renderiza el formulario de modificación del perfil del usuario.
     */
    public function edit($req, $res)
    {
        $validations = [];
        $error = $req->session['error'] ?? null;
        $success = $req->session['success'] ?? null;

        /*
         * Obtiene los mensajes de validación
         * de los campos del formulario.
         */
        foreach ($this->getFormFields() as $field) {
            $validations[$field] = $req->session['validations'][$field] ?? null;
        }

        foreach (['validations', 'error', 'success'] as $key) {
            unset($req->session[$key]);
        }

        $res->render('profile/edit', [
            'app' => $req->app,
            'userAuth' => $req->app->local('userAuth'),
            'validations' => $validations,
            'error' => $error,
            'success' => $success
        ]);
    }

    /*
     * Modifica el perfil del usuario.
     */
    public function update($req, $res)
    {
        $data = [];

        // Obtiene los valores de los campos del formulario.
        foreach ($this->getFormFields() as $field) {
            $data[$field] = $req->body[$field] ?? null;
        }

        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];

        $client = Api::client();

        // Realiza la petición de modificación del perfil del usuario.
        $response = $client->put('v1/profile', $headers, $data);

        $body = json_decode($response->body ?? '', true);

        // Comprueba el cuerpo de la petición.
        if (empty($response->success) || empty($body['data'])) {
            // Envía los mensajes de validación de los campos del formulario.
            if (!empty($body['validations'])) {
                $req->session['validations'] = $body['validations'];
            }

            // Envía el mensaje de error de la petición.
            $req->session['error'] = $body['error'] ?? 'The profile could not be updated';

            $res->redirect(Url::build('profile/edit'), StatusCode::FOUND);
        }

        $req->session['success'] = 'The profile was modified correctly';

        $res->redirect(Url::build('profile/edit'), StatusCode::FOUND);
    }
}
