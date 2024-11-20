<?php use App\Utils\Url, App\Utils\Html, App\Utils\Date ?>
<?php $app->render('layouts/header', ['title' => 'Users']) ?>
<?php $app->render('layouts/navbar', ['app' => $app]) ?>

<h1>Users</h1>

<a href="<?= Url::build('users/new') ?>" class="btn btn-default btn-ghost">
  Create user
</a>

<?php $app->render('layouts/footer') ?>
