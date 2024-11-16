<?php

namespace App\Controllers\Api;

use App\Models\NoteModel;
use App\Models\NoteTagModel;
use App\Models\TagModel;
use App\Utils\Crypt;
use App\Utils\DB;
use PH7\JustHttp\StatusCode;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class NoteController
{
    /*
     * Obtiene las reglas de validación.
     */
    private function getValidationRules()
    {
        return [
            'id' => v::stringType()->notEmpty()->Uuid(),
            'title' => v::stringType()->notEmpty()->length(1, 255, true),
            'body' => v::stringType()->notEmpty()->length(1, pow(2, 16) - 1, true),
            'tags' => v::arrayVal()->each(v::stringType()->notEmpty()->Uuid())
        ];
    }

    /*
     * Registra la nota de un usuario.
     */
    public function create($req, $res)
    {
        $data = [];

        // Obtiene los campos del cuerpo de la petición.
        foreach (['title', 'body', 'tags'] as $field) {
            if (v::key($field, v::notOptional(), true)->validate($req->body)) {
                $data[$field] = $req->body[$field];
            }
        }

        $rules = $this->getValidationRules();

        // Comprueba los campos del cuerpo de la petición.
        try {
            v::key('title', $rules['title'], true)
                ->key('body', $rules['body'], true)
                ->key('tags', $rules['tags'], false)
                ->assert($data);
        } catch (NestedValidationException $e) {
            $res->status(StatusCode::BAD_REQUEST)->json([
                'validations' => $e->getMessages()
            ]);
        }

        $userAuth = $req->app->local('userAuth');

        $tagModel = TagModel::factory();

        $tags = [];

        // Comprueba que los tags se encuentren registrados.
        if (!empty($data['tags'])) {
            $query = $tagModel->select('id');

            $params = [];

            foreach ($data['tags'] as $key => $value) {
                $paramName = ':id_' . $key;
                $query->param($paramName, $value);
                $params[] = $paramName;
            }

            $query->where(sprintf('id IN(%s)', implode(',', $params)));

            // Consulta los tags del usuario.
            $tags = $query->where('user_id', $userAuth['id'])->get();

            if (array_diff($data['tags'], array_column($tags, 'id'))) {
                $res->status(StatusCode::NOT_FOUND)->json([
                    'error' => 'Tags cannot be found'
                ]);
            }
        }

        $crypt = new Crypt($userAuth['id']);

        // Encripta el contenido de la nota.
        $data['body'] = $crypt->encrypt(trim($data['body']));

        $datetime = DB::datetime();

        // Genera el UUID de la nueva nota.
        $newNoteId = DB::generateUuid();

        $noteModel = NoteModel::factory();

        // Registra la información de la nueva nota.
        $noteModel->insert([
            'id' => $newNoteId,
            'user_id' => $userAuth['id'],
            'title' => trim($data['title']),
            'body' => $data['body'],
            'created_at' => $datetime,
            'updated_at' => $datetime
        ]);

        $noteTagModel = NoteTagModel::factory();

        // Relaciona los tags de la nota registrada.
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $datetime = DB::datetime();

                $noteTagModel->reset()->insert([
                    'id' => DB::generateUuid(),
                    'note_id' => $newNoteId,
                    'tag_id' => $tag['id'],
                    'created_at' => $datetime,
                    'updated_at' => $datetime
                ]);
            }
        }

        // Consulta la información de la nota registrada.
        $newNote = $noteModel
            ->reset()
            ->select('id, user_id, title, created_at, updated_at')
            ->find($newNoteId);

        // Consulta los tags de la nota registrada.
        $newNote['tags'] = $noteTagModel
            ->reset()
            ->select('tags.id, tags.name')
            ->tags()
            ->where('notes_tags.note_id', $newNote['id'])
            ->orderBy('tags.name ASC')
            ->get();

        $res->status(StatusCode::CREATED)->json([
            'data' => $newNote
        ]);
    }

    /*
     * Consulta las notas de un usuario.
     */
    public function index($req, $res)
    {
        $userAuth = $req->app->local('userAuth');

        $noteModel = NoteModel::factory();

        // Consulta la información de las notas del usuario.
        $notes = $noteModel
            ->select('notes.id, notes.user_id, notes.title, notes.created_at, notes.updated_at')
            ->where('notes.user_id', $userAuth['id'])
            ->orderBy('notes.updated_at DESC')
            ->get();

        $noteTagModel = NoteTagModel::factory();

        // Consulta los tags de las notas del usuario.
        foreach ($notes as &$note) {
            $note['tags'] = $noteTagModel
                ->reset()
                ->select('tags.id, tags.name')
                ->tags()
                ->where('notes_tags.note_id', $note['id'])
                ->orderBy('tags.name ASC')
                ->get();
        }

        unset($note);

        $res->json([
            'data' => $notes
        ]);
    }

    /*
     * Consulta la información de
     * la nota de un usuario.
     */
    public function show($req, $res)
    {
        $params = $req->params;

        $rules = $this->getValidationRules();

        // Comprueba los parámetros de la ruta.
        try {
            v::key('uuid', $rules['id'], true)->assert($params);
        } catch (NestedValidationException $e) {
            $res->status(StatusCode::BAD_REQUEST)->json([
                'error' => $e->getMessage()
            ]);
        }

        $userAuth = $req->app->local('userAuth');

        // Consulta la información de la nota.
        $note = NoteModel::factory()
            ->select('id, user_id, title, body, created_at, updated_at')
            ->where('user_id', $userAuth['id'])
            ->find($params['uuid']);

        // Comprueba que la nota se encuentra registrada.
        if (empty($note)) {
            $res->status(StatusCode::NOT_FOUND)->json([
                'error' => 'Note cannot be found'
            ]);
        }

        $crypt = new Crypt($userAuth['id']);

        // Desencripta el contenido de la nota.
        $note['body'] = $crypt->decrypt($note['body']);

        // Consulta los tags de la nota.
        $note['tags'] = NoteTagModel::factory()
            ->select('tags.id, tags.name')
            ->tags()
            ->where('notes_tags.note_id', $note['id'])
            ->orderBy('tags.name ASC')
            ->get();

        $res->json([
            'data' => $note
        ]);
    }

    /*
     * Modifica o actualiza la información
     * de la nota de un usuario.
     */
    public function update($req, $res)
    {
        $params = $req->params;

        $rules = $this->getValidationRules();

        // Comprueba los parámetros de la ruta.
        try {
            v::key('uuid', $rules['id'], true)->assert($params);
        } catch (NestedValidationException $e) {
            $res->status(StatusCode::BAD_REQUEST)->json([
                'error' => $e->getMessage()
            ]);
        }

        $data = [];

        // Obtiene los campos del cuerpo de la petición.
        foreach (['title', 'body', 'tags'] as $field) {
            if (v::key($field, v::notOptional(), true)->validate($req->body)) {
                $data[$field] = $req->body[$field];
            }
        }

        // Comprueba los campos del cuerpo de la petición.
        try {
            v::key('title', $rules['title'], false)
                ->key('body', $rules['body'], false)
                ->key('tags', $rules['tags'], false)
                ->assert($data);
        } catch (NestedValidationException $e) {
            $res->status(StatusCode::BAD_REQUEST)->json([
                'validations' => $e->getMessages()
            ]);
        }

        $userAuth = $req->app->local('userAuth');

        $noteModel = NoteModel::factory();

        // Consulta la información de la nota que será modificada.
        $note = $noteModel
            ->select('id')
            ->where('user_id', $userAuth['id'])
            ->find($params['uuid']);

        // Comprueba que la nota se encuentra registrada.
        if (empty($note)) {
            $res->status(StatusCode::NOT_FOUND)->json([
                'error' => 'Note cannot be found'
            ]);
        }

        $noteTagModel = NoteTagModel::factory();

        // Modifica total o parcialmente la información de la nota del usuario.
        if (!empty($data)) {
            $data['updated_at'] = DB::datetime();

            // $noteModel
            //     ->reset()
            //     ->where('id', $note['id'])
            //     ->update($data);
        }

        // Consulta la información de la nota modificada.
        $updatedNote = $noteModel
            ->reset()
            ->select('id, user_id, title, created_at, updated_at')
            ->find($note['id']);

        // Consulta los tags de la nota modificada.
        $updatedNote['tags'] = $noteTagModel
            ->select('tags.id, tags.name')
            ->tags()
            ->where('notes_tags.note_id', $updatedNote['id'])
            ->orderBy('tags.name ASC')
            ->get();

        $res->json([
            'data' => $updatedNote
        ]);
    }

    /*
     * Elimina la nota de un usuario.
     */
    public function delete($req, $res)
    {
        $params = $req->params;

        $rules = $this->getValidationRules();

        // Comprueba los parámetros de la ruta.
        try {
            v::key('uuid', $rules['id'], true)->assert($params);
        } catch (NestedValidationException $e) {
            $res->status(StatusCode::BAD_REQUEST)->json([
                'error' => $e->getMessage()
            ]);
        }

        $userAuth = $req->app->local('userAuth');

        $noteModel = NoteModel::factory();

        // Consulta la información de la nota que será eliminada.
        $deletedNote = $noteModel
            ->select('id, user_id, title, created_at, updated_at')
            ->where('user_id', $userAuth['id'])
            ->find($params['uuid']);

        // Comprueba que la nota se encuentra registrada.
        if (empty($deletedNote)) {
            $res->status(StatusCode::NOT_FOUND)->json([
                'error' => 'Note cannot be found'
            ]);
        }

        // Consulta los tags de la nota que será eliminada.
        $deletedNote['tags'] = NoteTagModel::factory()
            ->select('tags.id, tags.name')
            ->tags()
            ->where('notes_tags.note_id', $deletedNote['id'])
            ->orderBy('tags.name ASC')
            ->get();

        // Elimina la información de la nota.
        $noteModel
            ->reset()
            ->where('id', $deletedNote['id'])
            ->delete();

        $res->json([
            'data' => $deletedNote
        ]);
    }
}
