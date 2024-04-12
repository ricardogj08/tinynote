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

        $req->session['success'] = 'The tag was created correctly';

        $res->redirect(Url::build('tags'), StatusCode::FOUND);
    }

    /*
     * Consulta los tags del usuario.
     */
    public function index($req, $res)
    {
        $client = Api::client();

        // Realiza la petición de consulta de los tags del usuario.
        $response = $client->get('v1/tags');

        $body = json_decode($response->body ?? '', true);

        $tags = $body['data'] ?? [];

        $errors = $body['errors'] ?? $req->session['errors'] ?? null;

        $success = $req->session['success'] ?? null;

        unset($req->session['success'], $req->session['errors']);

        $res->render('tags/index', [
            'app' => $req->app,
            'tags' => $tags,
            'errors' => $errors,
            'success' => $success
        ]);
    }

    /*
     * Renderiza el formulario de modificación de tags.
     */
    public function edit($req, $res)
    {
        $errors = $req->session['errors'] ?? null;

        unset($req->session['errors']);

        $res->render('tags/edit', [
            'app' => $req->app,
            'errors' => $errors
        ]);
    }

    /*
     * Modifica el tag de un usuario.
     */
    public function update($req, $res)
    {
        $req->session['success'] = 'The tag was modified correctly';

        $res->redirect(Url::build('tags'), StatusCode::FOUND);
    }

    /*
     * Elimina el tag de un usuario.
     */
    public function delete($req, $res)
    {
        $client = Api::client();

        // Realiza la petición de eliminación del tag del usuario.
        $response = $client->delete('v1/tags/' . $req->params['uuid']);

        $body = json_decode($response->body ?? '', true);

        // Comprueba el cuerpo de la petición.
        if (empty($response->success)) {
            // Envía los errores de los campos del formulario.
            if (!empty($body['errors'])) {
                $req->session['errors'] = $body['errors'];
            }

            $res->redirect(Url::build('tags'), StatusCode::FOUND);
        }

        $req->session['success'] = 'The tag was deleted correctly';

        $res->redirect(Url::build('tags'), StatusCode::FOUND);
    }
}
