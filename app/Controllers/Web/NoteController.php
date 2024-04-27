<?php

namespace App\Controllers\Web;

use App\Utils\Api;
use App\Utils\Url;
use PH7\JustHttp\StatusCode;

class NoteController
{
    /*
     * Obtiene los nombres de los campos del formulario.
     */
    private function getFormFields()
    {
        return ['title', 'body', 'tags'];
    }

    /*
     * Renderiza el formulario de registro de notas.
     */
    public function new($req, $res)
    {
        $values = [];
        $validations = [];

        /*
         * Obtiene los valores y los mensajes de validación
         * de los campos del formulario.
         */
        foreach ($this->getFormFields() as $field) {
            $values[$field] = $req->session['values'][$field] ?? null;
            $validations[$field] = $req->session['validations'][$field] ?? null;
        }

        $client = Api::client();

        // Realiza la petición de consulta de los tags del usuario.
        $response = $client->get('v1/tags');

        $body = json_decode($response->body ?? '', true);

        $tags = $body['data'] ?? [];

        $error = $body['error'] ?? $req->session['error'] ?? null;

        foreach (['values', 'validations', 'error'] as $key) {
            unset($req->session[$key]);
        }

        $res->render('notes/new', [
            'app' => $req->app,
            'values' => $values,
            'validations' => $validations,
            'tags' => $tags,
            'error' => $error
        ]);
    }

    /*
     * Registra la nota de un usuario.
     */
    public function create($req, $res)
    {
        $data = [];

        // Obtiene los valores de los campos del formulario.
        foreach ($this->getFormFields() as $field) {
            $data[$field] = $req->body[$field] ?? null;
        }

        $client = Api::client();

        // Realiza la petición del registro de la nota del usuario.
        $response = $client->post('v1/notes', [], $data);

        $body = json_decode($response->body ?? '', true);

        // Comprueba el cuerpo de la petición.
        if (empty($response->success) || empty($body)) {
            $req->session['values'] = $data;

            // Envía los mensajes de validación de los campos del formulario.
            if (!empty($body['validations'])) {
                $req->session['validations'] = $body['validations'];
            }

            // Envía el mensaje de error de la petición.
            $req->session['error'] = $body['error'] ?? 'The note could not be created';

            $res->redirect(Url::build('notes/new'), StatusCode::FOUND);
        }

        $req->session['success'] = 'The note was created correctly';

        $res->redirect(Url::build('notes'), StatusCode::FOUND);
    }

    /*
     * Consulta las notas del usuario.
     */
    public function index($req, $res)
    {
        $client = Api::client();

        // Realiza la petición de consulta de las notas del usuario.
        $response = $client->get('v1/notes');

        $body = json_decode($response->body ?? '', true);

        $notes = $body['data'] ?? [];

        $error = $body['error'] ?? $req->session['error'] ?? null;

        $success = $req->session['success'] ?? null;

        foreach (['success', 'error'] as $key) {
            unset($req->session[$key]);
        }

        $res->render('notes/index', [
            'app' => $req->app,
            'notes' => $notes,
            'success' => $success,
            'error' => $error
        ]);
    }

    /*
     * Renderiza la nota de un usuario.
     */
    public function show($req, $res)
    {
        $uuid = $req->params['uuid'] ?? '';

        $client = Api::client();

        /*
         * Realiza la petición de consulta de
         * la información de la nota del usuario.
         */
        $response = $client->get('v1/notes/' . $uuid);

        $body = json_decode($response->body ?? '', true);

        $note = $body['data'] ?? [];

        // Comprueba el cuerpo de la petición.
        if (empty($response->success) || empty($note)) {
            // Envía el mensaje de error de la petición.
            $req->session['error'] = $body['error'] ?? 'The note could not be displayed';

            $res->redirect(Url::build('notes'), StatusCode::FOUND);
        }

        $res->render('notes/show', [
            'app' => $req->app,
            'note' => $note
        ]);
    }

    /*
     * Renderiza el formulario de modificación de notas.
     */
    public function edit($req, $res) {}

    /*
     * Modifica la nota de un usuario.
     */
    public function update($req, $res) {}

    /*
     * Elimina la nota de un usuario.
     */
    public function delete($req, $res)
    {
        $uuid = $req->params['uuid'] ?? '';

        $client = Api::client();

        // Realiza la petición de eliminación de la nota del usuario.
        $response = $client->delete('v1/notes/' . $uuid);

        $body = json_decode($response->body ?? '', true);

        // Comprueba el cuerpo de la petición.
        if (empty($response->success) || empty($body)) {
            // Envía el mensaje de error de la petición.
            $req->session['error'] = $body['error'] ?? 'The note could not be deleted';

            $res->redirect(Url::build('notes'), StatusCode::FOUND);
        }

        $req->session['success'] = 'The note was deleted correctly';

        $res->redirect(Url::build('notes'), StatusCode::FOUND);
    }
}
