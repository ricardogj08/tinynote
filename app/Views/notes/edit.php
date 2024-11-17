<?php use App\Utils\Url, App\Utils\Html ?>
<?php $app->render('layouts/header', ['title' => 'Edit note']) ?>
<?php $app->render('layouts/navbar', ['app' => $app]) ?>

<h1>Edit note</h1>

<?php $app->render('layouts/alerts/error', ['error' => $error]) ?>

<a href="<?= Url::build('notes') ?>">
  < Back
</a>

<form method="post" action="<?= Url::build(['notes', 'update', $note['id']]) ?>">
  <fieldset>
    <legend>Note editing</legend>

    <div class="form-group">
      <label for="title">
        Title:
      </label>
      <input
        type="text"
        id="title"
        name="title"
        placeholder="Enter note title"
        minlength="1"
        maxlength="255"
        required
        value="<?= Html::escape($note['title']) ?>">
      <small class="text-error"><?= Html::escape($validations['title']) ?></small>
    </div>

    <div class="form-group">
      <label for="body">
        Body:
      </label>
      <textarea
        id="textarea"
        name="body"
        placeholder="Enter note body"
        cols="30"
        rows="15"
        required
        minlength="1"
        maxlength="<?= pow(2, 16) - 1 ?>"><?= Html::simpleEscape($note['body']) ?></textarea>
      <small class="text-error"><?= Html::escape($validations['body']) ?></small>
    </div>

    <input type="submit" name="submit" value="Submit" class="btn btn-default">
  </fieldset>
</form>

<?php $app->render('layouts/footer') ?>
