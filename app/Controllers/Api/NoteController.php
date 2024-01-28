<?php

namespace App\Controllers\Api;

use App\Models\NoteModel;
use App\Models\TagModel;
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

        // Comprueba si los tags están registrados.
        if (!empty($data['tags'])) {
            $query = TagModel::Factory()->select('id');

            $params = [];

            foreach ($data['tags'] as $key => $value) {
                $paramName = ':id_' . $key;
                $query->param($paramName, $value);
                $params[] = $paramName;
            }

            $params = implode(', ', $params);

            $query->where("(id IN({$params}))");

            $tags = $query->where('user_id', $userAuth['id'])->get();

            exit (var_dump($tags));
        }
    }
}
