<?php use App\Utils\Url, App\Utils\Html ?>
<?php $app->render('layouts/header', ['title' => 'Create tag']) ?>
<?php $app->render('layouts/navbar', ['app' => $app]) ?>

<h1>Create tag</h1>

<?php $app->render('layouts/alerts/error', ['error' => $error]) ?>

<a href="<?= Url::build('tags') ?>">
  < Back
</a>

<form method="post" action="<?= Url::build('tags/create') ?>">
  <fieldset>
    <legend>Tag registration</legend>

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
        value="<?= Html::escape($values['name']) ?>">
        <small class="text-error"><?= Html::escape($validations['name']) ?></small>
    </div>

    <input type="submit" name="submit" value="Submit" class="btn btn-default btn-ghost">
  </fieldset>
</form>

<?php $app->render('layouts/footer') ?>
