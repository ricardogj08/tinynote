<?php

namespace App\Controllers\Web;

class ProfileController
{
    /*
     * Obtiene los nombres de los campos del formulario.
     */
    private function getFormFields()
    {
        return ['email', 'username', 'password', 'pass_confirm'];
    }

    /*
     * Renderiza el formulario de modificación del perfil del usuario.
     */
    public function edit($req, $res)
    {
        $validations = [];
        $error = $req->session['error'] ?? null;

        /*
         * Obtiene los mensajes de validación
         * de los campos del formulario.
         */
        foreach ($this->getFormFields() as $field) {
            $validations[$field] = $req->session['validations'][$field] ?? null;
        }

        foreach (['validations', 'error'] as $key) {
            unset($req->session[$key]);
        }

        $res->render('profile/edit', [
            'app' => $req->app,
            'userAuth' => $req->app->local('userAuth'),
            'validations' => $validations,
            'error' => $error
        ]);
    }

    /*
     * Modifica el perfil del usuario.
     */
    public function update($req, $res) {}
}
