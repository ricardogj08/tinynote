<?php $app->render('layouts/header') ?>

<form method="post" enctype="multipart/form-data" action="<?= \App\Utils\Url::build('notes') ?>">
  <fieldset>
    <legend>Note registration</legend>

    <div class="form-group">
      <label for="title">
        Title:
      </label>
      <input type="text" id="title" name="title">
    </div>

    <input type="submit" name="submit" value="Submit" class="btn btn-default">
  </fieldset>
</form>

<?php $app->render('layouts/footer') ?>
