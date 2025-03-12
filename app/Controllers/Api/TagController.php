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
            'name' => v::stringType()->NotEmpty()->length(1, 64, true)
        ];
    }

    /*
     * Registra el tag de un usuario.
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
            v::key('name', $rules['name'], true)->assert($data);
        } catch (NestedValidationException $e) {
            $res->status(StatusCode::BAD_REQUEST)->json([
                'validations' => $e->getMessages()
            ]);
        }

        // Limpia espacios sobrantes del nombre del nuevo tag.
        $data['name'] = trim($data['name']);

        $userAuth = $req->app->local('userAuth');

        $tagModel = TagModel::factory();

        $existsNewTag = $tagModel
            ->select('id')
            ->where('user_id', $userAuth['id'])
            ->where('name', $data['name'])
            ->value('id');

        // Comprueba que el tag sea único.
        if (!empty($existsNewTag)) {
            $res->status(StatusCode::CONFLICT)->json([
                'error' => 'A tag already exists with that name'
            ]);
        }

        // Genera el UUID del nuevo tag.
        $data['id'] = DB::generateUuid();

        // Relaciona la nota al usuario.
        $data['user_id'] = $userAuth['id'];

        $data['created_at'] = $data['updated_at'] = DB::datetime();

        // Registra la información del nuevo tag.
        $tagModel->reset()->insert($data);

        // Consulta la información del tag registrado.
        $newTag = $tagModel
            ->reset()
            ->select('id, name, user_id, created_at, updated_at')
            ->find($data['id']);

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
            ->select('tags.id, tags.name, tags.user_id, COUNT(notes_tags.id) AS number_notes, tags.created_at, tags.updated_at')
            ->notesTags()
            ->where('tags.user_id', $userAuth['id'])
            ->groupBy('tags.id')
            ->orderBy('tags.updated_at DESC')
            ->get();

        $res->json([
            'data' => $tags
        ]);
    }

    /*
     * Consulta la información del tag de un usuario.
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

        // Consulta la información del tag.
        $tag = TagModel::factory()
            ->select('tags.id, tags.name, tags.user_id, COUNT(notes_tags.id) AS number_notes, tags.created_at, tags.updated_at')
            ->notesTags()
            ->where('user_id', $userAuth['id'])
            ->groupBy('tags.id')
            ->find($params['uuid']);

        // Comprueba que el tag se encuentra registrado.
        if (empty($tag)) {
            $res->status(StatusCode::NOT_FOUND)->json([
                'error' => 'Tag cannot be found'
            ]);
        }

        $res->json([
            'data' => $tag
        ]);
    }

    /*
     * Modifica o actualiza la información
     * del tag de un usuario.
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

        $tagModel = TagModel::factory();

        // Consulta la información del tag que será modificado.
        $tag = $tagModel
            ->select('id')
            ->where('user_id', $userAuth['id'])
            ->find($params['uuid']);

        // Comprueba que el tag se encuentra registrado.
        if (empty($tag)) {
            $res->status(StatusCode::NOT_FOUND)->json([
                'error' => 'Tag cannot be found'
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
            v::key('name', $rules['name'], false)->assert($data);
        } catch (NestedValidationException $e) {
            $res->status(StatusCode::BAD_REQUEST)->json([
                'validations' => $e->getMessages()
            ]);
        }

        /*
         * Comprueba que el nombre del tag sea único
         * solo si encuentra presente.
         */
        if (v::key(('name'), v::notOptional(), true)->validate($data)) {
            $data['name'] = trim($data['name']);

            $existsTag = $tagModel
                ->reset()
                ->select('id')
                ->where('user_id', $userAuth['id'])
                ->where('name', $data['name'])
                ->where('id', '!=', $tag['id'])
                ->value('id');

            if (!empty($existsTag)) {
                $res->status(StatusCode::CONFLICT)->json([
                    'error' => 'A tag already exists with that name'
                ]);
            }
        }

        // Modifica total o parcialmente la información del tag del usuario.
        if (!empty($data)) {
            $data['updated_at'] = DB::datetime();

            $tagModel
                ->reset()
                ->where('id', $tag['id'])
                ->update($data);
        }

        // Consulta la información del tag modificado.
        $updatedTag = $tagModel
            ->reset()
            ->select('tags.id, tags.name, tags.user_id, COUNT(notes_tags.id) AS number_notes, tags.created_at, tags.updated_at')
            ->notesTags()
            ->groupBy('tags.id')
            ->find($tag['id']);

        $res->json([
            'data' => $updatedTag
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
                'error' => $e->getMessage()
            ]);
        }

        $userAuth = $req->app->local('userAuth');

        $tagModel = TagModel::factory();

        // Consulta la información del tag que será eliminado.
        $deletedTag = $tagModel
            ->select('tags.id, tags.name, tags.user_id, COUNT(notes_tags.id) AS number_notes, tags.created_at, tags.updated_at')
            ->notesTags()
            ->where('tags.user_id', $userAuth['id'])
            ->groupBy('tags.id')
            ->find($params['uuid']);

        // Comprueba que el tag se encuentra registrado.
        if (empty($deletedTag)) {
            $res->status(StatusCode::NOT_FOUND)->json([
                'error' => 'Tag cannot be found'
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
