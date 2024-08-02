<?php use App\Utils\Url, App\Utils\Html ?>
<?php $app->render('layouts/header', ['title' => 'Profile']) ?>
<?php $app->render('layouts/navbar', ['app' => $app]) ?>

<h1>Profile</h1>

<?php $app->render('layouts/alerts/error', ['error' => $error]) ?>
<?php $app->render('layouts/alerts/success', ['success' => $success]) ?>

<a href="<?= Url::build('notes') ?>">
  < Back
</a>

<form method="post" action="<?= Url::build('profile/update') ?>">
  <fieldset>
    <legend>Profile editing</legend>

    <div class="form-group">
      <label for="email">
        Email:
      </label>
      <input
        type="email"
        id="email"
        name="email"
        placeholder="Enter your email"
        value="<?= Html::escape($userAuth['email']) ?>">
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
        placeholder="Enter your username"
        value="<?= Html::escape($userAuth['username']) ?>">
      <small class="text-error"><?= Html::escape($validations['username']) ?></small>
    </div>

    <div class="form-group">
      <label for="password">
        Password:
      </label>
      <input
        type="password"
        id="password"
        name="password"
        placeholder="Enter your password"
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
        placeholder="Confirm your password"
        value="">
      <small class="text-error"><?= Html::escape($validations['pass_confirm']) ?></small>
    </div>

    <input type="submit" name="submit" value="Submit" class="btn btn-default">
  </fieldset>
</form>

<?php $app->render('layouts/footer') ?>
