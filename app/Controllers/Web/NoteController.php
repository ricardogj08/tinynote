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
        $errors = $req->session['errors'] ?? null;

        /*
         * Obtiene los valores y errores de validación
         * de los campos del formulario.
         */
        foreach ($this->getFormFields() as $field) {
            $values[$field] = $req->session['values'][$field] ?? null;
            $validations[$field] = $errors[$field] ?? null;
        }

        $client = Api::client();

        // Realiza la petición de consulta de los tags del usuario.
        $response = $client->get('v1/tags');

        $body = json_decode($response->body ?? '', true);

        $tags = $body['data'] ?? [];

        $errors = $body['errors'] ?? $errors;

        unset($req->session['values'], $req->session['errors']);

        $res->render('notes/new', [
            'app' => $req->app,
            'values' => $values,
            'validations' => $validations,
            'tags' => $tags,
            'errors' => $errors
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
        if (empty($response->success)) {
            $req->session['values'] = $data;

            // Envía los errores de los campos del formulario.
            if (!empty($body['errors'])) {
                $req->session['errors'] = $body['errors'];
            }

            $res->redirect(Url::build('notes/new'), StatusCode::FOUND);
        }

        $res->redirect(Url::build('notes'), StatusCode::FOUND);
    }

    /*
     * Consulta las notas del usuario.
     */
    public function index($req, $res) {}
}
