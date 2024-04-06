<?php $res->render('layouts/header', ['title' => 'Login']) ?>

<h1>Login</h1>

<form method="post" action="<?= \App\Utils\Url::build('login') ?>">
  <fieldset>
    <legend>Sign In</legend>

    <div class="form-group">
      <label for="nickname">
        Email/Username:
      </label>
      <input type="text" id="nickname" name="nickname" placeholder="Enter your email or username">
    </div>

    <div class="form-group">
      <label for="password">
        Password:
      </label>
      <input type="password" id="password" name="password" placeholder="Enter your password">
    </div>

    <div class="form-group">
      <input type="submit" name="submit" value="Login" class="btn btn-primary btn-block">
    </div>

    <a href="">
      Forgot password?
    </a>
  </fieldset>
</form>

<?php $res->render('layouts/footer') ?>
