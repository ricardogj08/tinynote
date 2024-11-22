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

        $rules = $this->getValidationRules();

        // Selecciona solo los campos necesarios.
        $fields = array_diff(array_keys($rules), ['id']);

        // Obtiene los campos del cuerpo de la petición.
        foreach ($fields as $field) {
            if (v::key($field, v::notOptional(), true)->validate($req->body)) {
                $data[$field] = $req->body[$field];
            }
        }

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

        $tags = [];

        // Comprueba que los tags se encuentren registrados.
        if (v::key('tags', v::notOptional(), true)->validate($data)) {
            $query = TagModel::factory()->select('id');

            $params = [];

            foreach ($data['tags'] as $key => $value) {
                $paramName = ':id_' . $key;
                $query->param($paramName, $value);
                $params[] = $paramName;
            }

            $query->where(sprintf('id IN(%s)', implode(',', $params)));

            // Consulta la información de los tags del usuario a relacionar en la nota.
            $tags = $query->where('user_id', $userAuth['id'])->get();

            // Comprueba que los tags enviados estén registrados.
            if (array_diff($data['tags'], array_column($tags, 'id'))) {
                $res->status(StatusCode::NOT_FOUND)->json([
                    'error' => 'The tags to add cannot be found'
                ]);
            }

            unset($data['tags']);
        }

        $crypt = new Crypt($userAuth['id']);

        // Encripta el contenido de la nota.
        $data['body'] = $crypt->encrypt(trim($data['body']));

        // Genera el UUID de la nueva nota.
        $data['id'] = DB::generateUuid();

        // Relaciona la nota al usuario.
        $data['user_id'] = $userAuth['id'];

        // Elimina espacios sobrantes del título de la nota.
        $data['title'] = trim($data['title']);

        $data['created_at'] = $data['updated_at'] = DB::datetime();

        $noteModel = NoteModel::factory();

        // Registra la información de la nueva nota.
        $noteModel->insert($data);

        $noteTagModel = NoteTagModel::factory();

        // Relaciona los tags de la nota registrada.
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $datetime = DB::datetime();

                $noteTagModel->reset()->insert([
                    'id' => DB::generateUuid(),
                    'note_id' => $data['id'],
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
            ->find($data['id']);

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

        $data = [];

        // Selecciona solo los campos necesarios.
        $fields = array_diff(array_keys($rules), ['id']);

        // Obtiene los campos del cuerpo de la petición.
        foreach ($fields as $field) {
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

        /*
         * Limpia espacios sobrantes del título de la nota
         * si se encuentra presente.
         */
        if (v::key('title', v::notOptional(), true)->validate($data)) {
            $data['title'] = trim($data['title']);
        }

        /*
         * Encripta el nuevo contenido de la nota
         * si se encuentra presente.
         */
        if (v::key('body', v::notOptional(), true)->validate($data)) {
            $crypt = new Crypt($userAuth['id']);

            $data['body'] = $crypt->encrypt(trim($data['body']));
        }

        $noteTagModel = NoteTagModel::factory();

        /*
         * Comprueba los tags de la nota que fueron modificados
         * si se encuentra presente.
         */
        if (v::key('tags', v::notOptional(), true)->validate($data)) {
            // Consulta los tags de la nota que será modificada.
            $note['tags'] = $noteTagModel
                ->select('tags.id, tags.name')
                ->tags()
                ->where('notes_tags.note_id', $note['id'])
                ->get();

            $noteTagsIDs = array_column($note['tags'], 'id');

            // Obtiene los nuevos tags relacionados a la nota.
            $newNoteTags = array_diff($data['tags'], $noteTagsIDs);

            // Comprueba y relaciona los nuevos tags a la nota.
            if (!empty($newNoteTags)) {
                $query = TagModel::factory()->select('id');

                $params = [];

                foreach ($newNoteTags as $key => $value) {
                    $paramName = ':id_' . $key;
                    $query->param($paramName, $value);
                    $params[] = $paramName;
                }

                $query->where(sprintf('id IN(%s)', implode(',', $params)));

                // Consulta la información de los nuevos tags a relacionar en la nota.
                $newTagsToAdd = $query->where('user_id', $userAuth['id'])->get();

                // Comprueba que los tags enviados estén registrados.
                if (array_diff($newNoteTags, array_column($newTagsToAdd, 'id'))) {
                    $res->status(StatusCode::NOT_FOUND)->json([
                        'error' => 'The new tags to add cannot be found'
                    ]);
                }

                // Relaciona los nuevos tags de la nota.
                foreach ($newTagsToAdd as $tag) {
                    $datetime = DB::datetime();

                    $noteTagModel->reset()->insert([
                        'id' => DB::generateUuid(),
                        'note_id' => $note['id'],
                        'tag_id' => $tag['id'],
                        'created_at' => $datetime,
                        'updated_at' => $datetime
                    ]);
                }
            }

            // Obtiene los tags eliminados de la nota.
            $deletedNoteTags = array_diff($noteTagsIDs, $data['tags']);

            // Elimina los tags de la nota.
            if (!empty($deletedNoteTags)) {
                $deleteQuery = $noteTagModel
                    ->reset()
                    ->where('note_id', $note['id']);

                $deleteParams = [];

                foreach ($deletedNoteTags as $key => $value) {
                    $deleteParamName = ':id_' . $key;
                    $deleteQuery->param($deleteParamName, $value);
                    $deleteParams[] = $deleteParamName;
                }

                // Elimina la información de los tags que no se encuentran.
                $deleteQuery
                    ->where(sprintf('tag_id IN(%s)', implode(',', $deleteParams)))
                    ->delete();
            }

            unset($data['tags']);
        }

        // Modifica total o parcialmente la información de la nota del usuario.
        if (!empty($data)) {
            $data['updated_at'] = DB::datetime();

            $noteModel
                ->reset()
                ->where('id', $note['id'])
                ->update($data);
        }

        // Consulta la información de la nota modificada.
        $updatedNote = $noteModel
            ->reset()
            ->select('id, user_id, title, created_at, updated_at')
            ->find($note['id']);

        // Consulta los tags de la nota modificada.
        $updatedNote['tags'] = $noteTagModel
            ->reset()
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
