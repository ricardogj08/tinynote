<?php use App\Utils\Url, App\Utils\Html ?>
<?php $app->render('layouts/header', ['title' => $note['title']]) ?>
<?php $app->render('layouts/navbar', ['app' => $app]) ?>

<a href="<?= Url::build('notes') ?>">
  < Back
</a>

<h1><?= Html::escape($note['title']) ?></h1>

<?= Html::fromMarkdown($note['body']) ?>

<?php $app->render('layouts/footer') ?>
