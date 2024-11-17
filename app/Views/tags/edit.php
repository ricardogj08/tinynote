<?php use App\Utils\Url, App\Utils\Html ?>
<?php $app->render('layouts/header', ['title' => 'Edit tag']) ?>
<?php $app->render('layouts/navbar', ['app' => $app]) ?>

<h1>Edit tag</h1>

<?php $app->render('layouts/alerts/error', ['error' => $error]) ?>

<a href="<?= Url::build('tags') ?>">
  < Back
</a>

<form method="post" action="<?= Url::build(['tags', 'update', $tag['id']]) ?>">
  <fieldset>
    <legend>Tag editing</legend>

    <div class="form-group">
      <label for="name">
        Name:
      </label>
      <input
        type="text"
        id="name"
        name="name"
        placeholder="Enter tag name"
        minlength="1"
        maxlength="64"
        required
        value="<?= Html::escape($tag['name']) ?>">
      <small class="text-error"><?= Html::escape($validations['name']) ?></small>
    </div>

    <input type="submit" name="submit" value="Submit" class="btn btn-default">
  </fieldset>
</form>

<?php $app->render('layouts/footer') ?>
