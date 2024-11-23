<?php use App\Utils\Url, App\Utils\Html ?>
<?php $app->render('layouts/header', ['title' => 'Edit note']) ?>
<?php $app->render('layouts/navbar', ['app' => $app]) ?>

<h1>Edit note</h1>

<?php $app->render('layouts/alerts/error', ['error' => $error]) ?>
<?php $app->render('layouts/alerts/success', ['success' => $success]) ?>

<a href="<?= Url::build('notes') ?>">
  < Back
</a>

<form method="post" enctype="multipart/form-data" action="<?= Url::build(['notes', 'update', $note['id']]) ?>">
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
        Body (markdown):
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

    <div class="form-group">
      <label for="tags">
        Tags:
      </label>
      <select id="tags" name="tags[]" multiple size="4">
        <?php foreach ($tags as $tag): ?>
          <option value="<?= Html::escape($tag['id']) ?>" <?= $tag['selected'] ? 'selected' : '' ?>>
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

      <small>*Hold down the <kbd>Ctrl</kbd> (Windows) or <kbd>Command</kbd> (Mac) button to select/deselect multiple options.</small>
    </div>

    <input type="hidden" name="csrf_token" value="<?= $app->local('csrf_token') ?? '' ?>">

    <input type="submit" name="submit" value="Save" class="btn btn-default">
  </fieldset>
</form>

<?php $app->render('layouts/footer') ?>
