<?php

namespace App\Controllers\Web;

use App\Utils\Api;
use App\Utils\Url;
use PH7\JustHttp\StatusCode;

class TagController
{
    /*
     * Renderiza el formulario de registro de tags.
     */
    public function new($req, $res)
    {
        $errors = $req->session['errors'] ?? null;

        // Obtiene el valor y error del campo del formulario.
        $values = ['name' => $req->session['values']['name'] ?? null];
        $validations = ['name' => $errors['name'] ?? null];

        unset($req->session['values'], $req->session['errors']);

        $res->render('tags/new', [
            'app' => $req->app,
            'values' => $values,
            'validations' => $validations,
            'errors' => $errors
        ]);
    }

    /*
     * Registra el tag de un usuario.
     */
    public function create($req, $res)
    {
        // Obtiene el valor del campo del formulario.
        $data = ['name' => $req->body['name'] ?? null];

        $client = Api::client();

        // Realiza la petición del registro del tag del usuario.
        $response = $client->post('v1/tags', [], $data);

        $body = json_decode($response->body ?? '', true);

        // Comprueba el cuerpo de la petición.
        if (empty($response->success)) {
            $req->session['values'] = $data;

            // Envía los errores de los campos del formulario.
            if (!empty($body['errors'])) {
                $req->session['errors'] = $body['errors'];
            }

            $res->redirect(Url::build('tags/new'), StatusCode::FOUND);
        }

        $res->redirect(Url::build('tags'), StatusCode::FOUND);
    }

    /*
     * Consulta los tags del usuario.
     */
    public function index($req, $res) {}
}
