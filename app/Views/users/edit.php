<?php use App\Utils\Url, App\Utils\Html ?>
<?php $app->render('layouts/header', ['title' => 'Edit user']) ?>
<?php $app->render('layouts/navbar', ['app' => $app]) ?>

<h1>Edit user</h1>

<a href="<?= Url::build('users') ?>">
  < Back
</a>

<?php $app->render('layouts/footer') ?>
