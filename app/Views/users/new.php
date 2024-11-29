<?php use App\Utils\Url, App\Utils\Html, Respect\Validation\Validator as v ?>
<?php $app->render('layouts/header', ['title' => 'Create user']) ?>
<?php $app->render('layouts/navbar', ['app' => $app]) ?>

<h1>Create user</h1>

<?php $app->render('layouts/alerts/error', ['error' => $error]) ?>

<a href="<?= Url::build('users') ?>">
  < Back
</a>

<form method="post" action="<?= Url::build('users/create') ?>">
  <fieldset>
    <legend>User registration</legend>

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
        value="<?= Html::escape($values['email']) ?>">
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
        value="<?= Html::escape($values['username']) ?>">
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
        <?= v::notOptional()->trueVal()->validate($values['active']) ? 'checked' : '' ?>>
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
        <?= v::notOptional()->trueVal()->validate($values['is_admin']) ? 'checked' : '' ?>>
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
        required
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
        required
        value="">
      <small class="text-error"><?= Html::escape($validations['pass_confirm']) ?></small>
    </div>

    <input type="hidden" name="csrf_token" value="<?= Html::escape($app->local('csrf_token') ?? null) ?>">

    <input type="submit" name="submit" value="Submit" class="btn btn-default">
  </fieldset>
</form>

<?php $app->render('layouts/footer') ?>
