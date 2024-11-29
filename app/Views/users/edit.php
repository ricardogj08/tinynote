<?php use App\Utils\Url, App\Utils\Html, Respect\Validation\Validator as v ?>
<?php $app->render('layouts/header', ['title' => 'Edit user']) ?>
<?php $app->render('layouts/navbar', ['app' => $app]) ?>

<h1>Edit user</h1>

<?php $app->render('layouts/alerts/error', ['error' => $error]) ?>
<?php $app->render('layouts/alerts/success', ['success' => $success]) ?>

<a href="<?= Url::build('users') ?>">
  < Back
</a>

<form method="post" action="<?= Url::build(['users', 'update', $user['id']]) ?>">
  <fieldset>
    <legend>User editing</legend>

    <div class="form-group">
      <label for="email">
        Email:
      </label>
      <input
        type="email"
        id="email"
        name="email"
        placeholder="Enter email"
        minlength="4"
        maxlength="255"
        required
        value="<?= Html::escape($user['email']) ?>">
      <small class="text-error"><?= Html::escape($validations['email']) ?></small>
    </div>

    <div class="form-group">
      <label for="username">
        Username:
      </label>
      <input
        type="text"
        id="username"
        name="username"
        placeholder="Enter username"
        minlength="4"
        maxlength="32"
        required
        value="<?= Html::escape($user['username']) ?>">
      <small class="text-error"><?= Html::escape($validations['username']) ?></small>
    </div>

    <div class="form-group">
      <label for="active">
        Active:
      </label>
      <input
        type="checkbox"
        id="active"
        name="active"
        value="true"
        <?= v::notOptional()->trueVal()->validate($user['active']) ? 'checked' : '' ?>>
      <small class="text-error"><?= Html::escape($validations['active']) ?></small>
    </div>

    <div class="form-group">
      <label for="is_admin">
        Is admin?:
      </label>
      <input
        type="checkbox"
        id="is_admin"
        name="is_admin"
        value="true"
        <?= v::notOptional()->trueVal()->validate($user['is_admin']) ? 'checked' : '' ?>>
      <small class="text-error"><?= Html::escape($validations['is_admin']) ?></small>
    </div>

    <div class="form-group">
      <label for="password">
        Password:
      </label>
      <input
        type="password"
        id="password"
        name="password"
        placeholder="Enter password"
        minlength="8"
        maxlength="64"
        value="">
      <small class="text-error"><?= Html::escape($validations['password']) ?></small>
    </div>

    <div class="form-group">
      <label for="pass_confirm">
        Confirm password:
      </label>
      <input
        type="password"
        id="pass_confirm"
        name="pass_confirm"
        placeholder="Confirm password"
        minlength="8"
        maxlength="64"
        value="">
      <small class="text-error"><?= Html::escape($validations['pass_confirm']) ?></small>
    </div>

    <input type="hidden" name="csrf_token" value="<?= Html::escape($app->local('csrf_token') ?? null) ?>">

    <input type="submit" name="submit" value="Save" class="btn btn-default">
  </fieldset>
</form>

<?php $app->render('layouts/footer') ?>
