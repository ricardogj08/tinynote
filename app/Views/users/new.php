<?php use App\Utils\Url, App\Utils\Html ?>
<?php $app->render('layouts/header', ['title' => 'Create user']) ?>
<?php $app->render('layouts/navbar', ['app' => $app]) ?>

<h1>Create user</h1>

<a href="<?= Url::build('users') ?>">
  < Back
</a>

<?php $app->render('layouts/footer') ?>
