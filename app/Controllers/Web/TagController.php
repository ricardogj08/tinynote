<?php

namespace App\Controllers\Web;

use App\Utils\Api;
use App\Utils\Url;
use PH7\JustHttp\StatusCode;

class TagController
{
    /*
     * Obtiene los nombres de los campos del formulario.
     */
    private function getFormFields()
    {
        return ['name'];
    }

    /*
     * Renderiza el formulario de registro de tags.
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

        $res->render('tags/new', [
            'app' => $req->app,
            'values' => $values,
            'validations' => $validations,
            'error' => $error
        ]);
    }

    /*
     * Registra el tag de un usuario.
     */
    public function create($req, $res)
    {
        $data = [];

        // Obtiene los valores de los campos del formulario.
        foreach ($this->getFormFields() as $field) {
            $data[$field] = $req->body[$field] ?? null;
        }

        $client = Api::client();

        // Realiza la petición del registro del tag del usuario.
        $response = $client->post('v1/tags', [], $data);

        $body = json_decode($response->body ?? '', true);

        // Comprueba el cuerpo de la petición.
        if (empty($response->success) || empty($body['data'])) {
            $req->session['values'] = $data;

            // Envía los mensajes de validación de los campos del formulario.
            if (!empty($body['validations'])) {
                $req->session['validations'] = $body['validations'];
            }

            // Envía el mensaje de error de la petición..
            $req->session['error'] = $body['error'] ?? 'The tag could not be created';

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

        $error = $body['error'] ?? $req->session['error'] ?? null;

        $success = $req->session['success'] ?? null;

        foreach (['success', 'error'] as $key) {
            unset($req->session[$key]);
        }

        $res->render('tags/index', [
            'app' => $req->app,
            'tags' => $tags,
            'success' => $success,
            'error' => $error
        ]);
    }

    /*
     * Renderiza el formulario de modificación de tags.
     */
    public function edit($req, $res)
    {
        $uuid = $req->params['uuid'] ?? '';

        $client = Api::client();

        /*
         * Realiza la petición de consulta de
         * la información del tag del usuario.
         */
        $response = $client->get('v1/tags/' . $uuid);

        $body = json_decode($response->body ?? '', true);

        $tag = $body['data'] ?? [];

        // Comprueba el cuerpo de la petición.
        if (empty($response->success) || empty($tag)) {
            // Envía el mensaje de error de la petición.
            $req->session['error'] = $body['error'] ?? 'The tag could not be edited';

            $res->redirect(Url::build('tags'), StatusCode::FOUND);
        }

        $validations = [];
        $error = $req->session['error'] ?? null;

        /*
         * Obtiene los valores y los mensajes de validación
         * de los campos del formulario.
         */
        foreach ($this->getFormFields() as $field) {
            $validations[$field] = $req->session['validations'][$field] ?? null;
        }

        foreach (['validations', 'error'] as $key) {
            unset($req->session[$key]);
        }

        $res->render('tags/edit', [
            'app' => $req->app,
            'tag' => $tag,
            'validations' => $validations,
            'error' => $error
        ]);
    }

    /*
     * Modifica el tag de un usuario.
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

        // Realiza la petición de modificación del tag del usuario.
        $response = $client->put('v1/tags/' . $uuid, $headers, $data);

        $body = json_decode($response->body ?? '', true);

        // Comprueba el cuerpo de la petición.
        if (empty($response->success) || empty($body['data'])) {
            // Envía los mensajes de validación de los campos del formulario.
            if (!empty($body['validations'])) {
                $req->session['validations'] = $body['validations'];
            }

            // Envía el mensaje de error de la petición.
            $req->session['error'] = $body['error'] ?? 'The tag could not be updated';

            $res->redirect(Url::build('tags/edit/' . $uuid), StatusCode::FOUND);
        }

        $req->session['success'] = 'The tag was modified correctly';

        $res->redirect(Url::build('tags'), StatusCode::FOUND);
    }

    /*
     * Elimina el tag de un usuario.
     */
    public function delete($req, $res)
    {
        $uuid = $req->params['uuid'] ?? '';

        $client = Api::client();

        // Realiza la petición de eliminación del tag del usuario.
        $response = $client->delete('v1/tags/' . $uuid);

        $body = json_decode($response->body ?? '', true);

        // Comprueba el cuerpo de la petición.
        if (empty($response->success) || empty($body['data'])) {
            // Envía el mensaje de error de la petición.
            $req->session['error'] = $body['error'] ?? 'The tag could not be deleted';

            $res->redirect(Url::build('tags'), StatusCode::FOUND);
        }

        $req->session['success'] = 'The tag was deleted correctly';

        $res->redirect(Url::build('tags'), StatusCode::FOUND);
    }
}
