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
            'title' => v::stringType()->notEmpty()->length(null, 255, true),
            'body' => v::stringType()->notEmpty(),
            'tags' => v::optional(v::arrayVal()->each(v::stringType()->Uuid()))
        ];
    }

    /*
     * Registra la nota de un usuario.
     */
    public function create($req, $res)
    {
        $data = $req->body;

        $rules = $this->getValidationRules();

        // Comprueba los campos del cuerpo de la petición.
        try {
            v::key('title', $rules['title'], true)
                ->key('body', $rules['body'], true)
                ->key('tags', $rules['tags'], false)
                ->assert($data);
        } catch (NestedValidationException $e) {
            $res->status(StatusCode::BAD_REQUEST)->json([
                'errors' => $e->getMessages()
            ]);
        }

        $userAuth = $req->app->local('userAuth');

        $tagModel = TagModel::factory();

        // Comprueba que los tags se encuentren registrados.
        if (!empty($data['tags'])) {
            $query = $tagModel->select('id');

            $params = [];

            foreach ($data['tags'] as $key => $value) {
                $paramName = ':id_' . $key;
                $query->param($paramName, $value);
                $params[] = $paramName;
            }

            $params = implode(',', $params);

            $query->where("id IN({$params})");

            $tags = $query->where('user_id', $userAuth['id'])->get();

            if (array_diff($data['tags'], array_column($tags, 'id'))) {
                $res->status(StatusCode::NOT_FOUND)->json([
                    'errors' => 'Tags cannot be found'
                ]);
            }
        }

        $crypt = new Crypt($userAuth['id']);

        // Encripta el cuerpo de la nota.
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
            ->select('id, user_id, title, body, created_at, updated_at')
            ->find($newNoteId);

        $newNote['body'] = $crypt->decrypt($newNote['body']);

        // Consulta los tags de la nota registrada.
        $newNote['tags'] = $noteTagModel
            ->reset()
            ->select('tags.id, tags.name')
            ->tags()
            ->where('notes_tags.note_id', $newNote['id'])
            ->groupBy('tags.id')
            ->get();

        $res->status(StatusCode::CREATED)->json([
            'data' => $newNote
        ]);
    }
}
