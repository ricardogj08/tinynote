<?php use App\Utils\Url, App\Utils\Html ?>
<?php $app->render('layouts/header', ['title' => 'Login']) ?>

<h1>Login</h1>

<?php $app->render('layouts/alerts/error', ['error' => $error]) ?>

<form method="post" action="<?= Url::build('login') ?>">
  <fieldset>
    <legend>Sign In</legend>

    <div class="form-group">
      <label for="nickname">
        Email/Username:
      </label>
      <input
        type="text"
        id="nickname"
        name="nickname"
        placeholder="Enter your email or username"
        minlength="4"
        maxlength="255"
        required
        value="<?= Html::escape($values['nickname']) ?>">
      <small class="text-error"><?= Html::escape($validations['nickname']) ?></small>
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
        minlength="8"
        maxlength="64"
        required
        value="">
      <small class="text-error"><?= Html::escape($validations['password']) ?></small>
    </div>

    <input type="hidden" name="csrf_token" value="<?= $app->local('csrf_token') ?? '' ?>">

    <div class="form-group">
      <input type="submit" name="submit" value="Login" class="btn btn-primary btn-block">
    </div>

    <a href="">
      Forgot password?
    </a>
  </fieldset>
</form>

<?php $app->render('layouts/footer') ?>
