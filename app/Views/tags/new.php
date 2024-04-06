<?php $res->render('layouts/header') ?>

<form method="post" action="<?= \App\Utils\Url::build('tags') ?>">
  <fieldset>
  	<legend>
  		Tag registration
  	</legend>

    <div class="form-group">
      <label for="name">
        Name:
      </label>
      <input type="text" id="name" name="name">
    </div>

    <input type="submit" name="submit" value="Submit" class="btn btn-default">
  </fieldset>
</form>

<?php $res->render('layouts/footer') ?>
