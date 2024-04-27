<?php use App\Utils\Url, App\Utils\Html, App\Utils\Date ?>
<?php $app->render('layouts/header', ['title' => 'Tags']) ?>
<?php $app->render('layouts/navbar', ['app' => $app]) ?>

<h1>Tags</h1>

<?php $app->render('layouts/alerts/error', ['error' => $error]) ?>
<?php $app->render('layouts/alerts/success', ['success' => $success]) ?>

<a href="<?= Url::build('tags/new') ?>" class="btn btn-default btn-ghost">
 Create tag
</a>

<table>
  <caption>Registered tags</caption>
  <thead>
    <tr>
      <th>Name</th>
      <th>Num. notes</th>
      <th>Created</th>
      <th>Updated</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($tags as $tag): ?>
      <tr>
        <td><?= Html::escape($tag['name']) ?></td>
        <td><?= Html::escape($tag['number_notes']) ?></td>
        <td><?= Html::escape(Date::humanize($tag['created_at'])) ?></td>
        <td><?= Html::escape(Date::humanize($tag['updated_at'])) ?></td>
        <td>
          <a href="<?= Url::build(['tags', 'edit', $tag['id']]) ?>">
            Edit
          </a>
          <a href="<?= Url::build(['tags', 'delete', $tag['id']]) ?>">
            Delete
          </a>
        </td>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>

<?php $app->render('layouts/footer') ?>
