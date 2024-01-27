<?php

namespace App\Controllers;

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

        $userAuth = $req->app->local('userAuth');

        $data['name'] = trim($data['name']);

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
}
