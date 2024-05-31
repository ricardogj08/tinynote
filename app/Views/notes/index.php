<?php use App\Utils\Url, App\Utils\Html, App\Utils\Date ?>
<?php $app->render('layouts/header', ['title' => 'Notes']) ?>
<?php $app->render('layouts/navbar', ['app' => $app]) ?>

<h1>Notes</h1>

<?php $app->render('layouts/alerts/error', ['error' => $error]) ?>
<?php $app->render('layouts/alerts/success', ['success' => $success]) ?>

<a href="<?= Url::build('notes/new') ?>" class="btn btn-default btn-ghost">
  Create note
</a>

<?php foreach ($notes as $note): ?>
  <hr>
  <article class="terminal-card">
    <header>
      <?= Html::escape($note['title']) ?>
    </header>
    <div>
      <p>Created: <?= Date::humanize($note['created_at']) ?></p>
      <p>Updated: <?= Date::humanize($note['updated_at']) ?></p>
      <p>
        <?php foreach ($note['tags'] as $tag): ?>
          <a href="">
            <?= Html::escape($tag['name']) ?>
          </a>
        <?php endforeach ?>
      </p>
      <footer>
        <a href="<?= Url::build(['notes', $note['id']]) ?>" class="btn btn-default btn-ghost">
          View
        </a>
        <a href="<?= Url::build(['notes', 'edit', $note['id']]) ?>" class="btn btn-primary btn-ghost">
          Edit
        </a>
        <a href="<?= Url::build(['notes', 'delete', $note['id']]) ?>" class="btn btn-error btn-ghost">
          Delete
        </a>
      </footer>
    </div>
  </article>
<?php endforeach ?>

<?php $app->render('layouts/footer') ?>
