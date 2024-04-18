<?php use App\Utils\Url, App\Utils\Html ?>
<?php $app->render('layouts/header', ['title' => $note['title']]) ?>
<?php $app->render('layouts/navbar', ['app' => $app]) ?>

<a href="<?= Url::build('notes') ?>">
  < Back
</a>

<?= Html::markdown($note['body']) ?>

<?php $app->render('layouts/footer') ?>
