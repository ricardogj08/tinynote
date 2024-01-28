<?php

namespace App\Controllers\Api;

use App\Models\TagModel;
use App\Utils\DB;
use PH7\JustHttp\StatusCode;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class TagController
{
    /*
     * Obtiene las reglas de validación.
     */
    private function getValidationRules()
    {
        return [
            'id' => v::stringType()->NotEmpty()->Uuid(),
            'name' => v::stringType()->NotEmpty()->length(null, 64, true)
        ];
    }

    /*
     * Registra el tag de un usuario.
     */
    public function create($req, $res)
    {
        $data = $req->body;

        $rules = $this->getValidationRules();

        // Comprueba los campos del cuerpo de la petición.
        try {
            v::key('name', $rules['name'], true)->assert($data);
        } catch (NestedValidationException $e) {
            $res->status(StatusCode::BAD_REQUEST)->json([
                'errors' => $e->getMessages()
            ]);
        }

        $data['name'] = trim($data['name']);

        $userAuth = $req->app->local('userAuth');

        $tagModel = TagModel::factory();

        $existsNewTag = $tagModel
            ->where('user_id', $userAuth['id'])
            ->where('name', $data['name'])
            ->value('id');

        // Comprueba que el tag sea único.
        if (!empty($existsNewTag)) {
            $res->status(StatusCode::CONFLICT)->json([
                'errors' => 'A tag already exists with that name'
            ]);
        }

        // Genera el UUID del nuevo tag.
        $newTagId = DB::generateUuid();

        $datetime = DB::datetime();

        // Registra la información del nuevo tag.
        $tagModel->reset()->insert([
            'id' => $newTagId,
            'user_id' => $userAuth['id'],
            'name' => $data['name'],
            'created_at' => $datetime,
            'updated_at' => $datetime
        ]);

        // Consulta la información del tag registrado.
        $newTag = $tagModel
            ->reset()
            ->select('id, name, user_id, created_at, updated_at')
            ->find($newTagId);

        $res->status(StatusCode::CREATED)->json([
            'data' => $newTag
        ]);
    }

    /*
     * Consulta los tags de un usuario.
     */
    public function index($req, $res)
    {
        $userAuth = $req->app->local('userAuth');

        $tagModel = tagModel::factory();

        // Consulta la información de los tags del usuario.
        $tags = $tagModel
            ->select('tags.id, tags.name, COUNT(notes.id) AS number_notes, tags.created_at, tags.updated_at')
            ->notes()
            ->where('tags.user_id', $userAuth['id'])
            ->groupBy('tags.id')
            ->get();

        $res->json([
            'data' => $tags
        ]);
    }

    /*
     * Elimina el tag de un usuario.
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
                'errors' => $e->getMessages()
            ]);
        }

        $userAuth = $req->app->local('userAuth');

        $tagModel = TagModel::factory();

        // Consulta la información del tag que será eliminado.
        $deletedTag = $tagModel
            ->select('tags.id, tags.name, COUNT(notes.id) AS number_notes, tags.created_at, tags.updated_at')
            ->notes()
            ->where('tags.user_id', $userAuth['id'])
            ->groupBy('tags.id')
            ->find($params['uuid']);

        // Comprueba que el tag se encuentra registrado.
        if (empty($deletedTag)) {
            $res->status(StatusCode::NOT_FOUND)->json([
                'errors' => 'Tag cannot be found'
            ]);
        }

        // Elimina la información del tag.
        $tagModel
            ->reset()
            ->where('id', $deletedTag['id'])
            ->delete();

        $res->json([
            'data' => $deletedTag
        ]);
    }
}
