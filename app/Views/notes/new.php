<?php use App\Utils\Url, App\Utils\Html ?>
<?php $app->render('layouts/header', ['title' => 'Create note']) ?>
<?php $app->render('layouts/navbar', ['app' => $app]) ?>

<h1>Create note</h1>

<?php $app->render('layouts/alerts/error', ['error' => $error]) ?>

<a href="<?= Url::build('notes') ?>">
  < Back
</a>

<form method="post" enctype="multipart/form-data" action="<?= Url::build('notes/create') ?>">
  <fieldset>
    <legend>Note registration</legend>

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
        value="<?= Html::escape($values['title']) ?>">
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
        maxlength="<?= pow(2, 16) - 1 ?>"><?= Html::simpleEscape($values['body']) ?></textarea>
        <small class="text-error"><?= Html::escape($validations['body']) ?></small>
    </div>

    <div class="form-group">
      <label for="tags">
        Tags:
      </label>
      <select id="tags" name="tags[]" multiple size="4">
        <?php foreach ($tags as $tag): ?>
          <option value="<?= Html::escape($tag['id']) ?>">
            <?= Html::escape($tag['name']) ?>
          </option>
        <?php endforeach ?>
      </select>

      <?php if (is_array($validations['tags'])): ?>
        <?php foreach ($validations['tags'] as $key => $value): ?>
          <small class="text-error"><?= Html::escape($value) ?></small>
        <?php endforeach ?>
      <?php else: ?>
        <small class="text-error"><?= Html::escape($validations['tags']) ?></small>
      <?php endif ?>
    </div>

    <input type="submit" name="submit" value="Submit" class="btn btn-default btn-ghost">
  </fieldset>
</form>

<?php $app->render('layouts/footer') ?>
