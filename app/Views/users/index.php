<?php use App\Utils\Url, App\Utils\Html, App\Utils\Date ?>
<?php $app->render('layouts/header', ['title' => 'Users']) ?>
<?php $app->render('layouts/navbar', ['app' => $app]) ?>

<h1>Users</h1>

<?php $app->render('layouts/alerts/error', ['error' => $error]) ?>
<?php $app->render('layouts/alerts/success', ['success' => $success]) ?>

<a href="<?= Url::build('users/new') ?>" class="btn btn-default btn-ghost">
  Create user
</a>

<table>
  <caption>Registered users</caption>
  <thead>
    <tr>
      <th>Username</th>
      <th>Email</th>
      <th>Status</th>
      <th>Role</th>
      <th>Num. notes</th>
      <th>Num. tags</th>
      <th>Created</th>
      <th>Updated</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
      <tr>
        <td><?= Html::escape($user['username']) ?></td>
        <td><?= Html::escape($user['email']) ?></td>
        <td><?= $user['active'] ? 'Active' : 'Inactive' ?></td>
        <td><?= $user['is_admin'] ? 'Admin' : 'User' ?></td>
        <td><?= Html::escape($user['number_notes']) ?></td>
        <td><?= Html::escape($user['number_tags']) ?></td>
        <td><?= Html::escape(Date::humanize($user['created_at'])) ?></td>
        <td><?= Html::escape(Date::humanize($user['updated_at'])) ?></td>
        <td>
          <a href="<?= Url::build(['users', 'edit', $user['id']]) ?>">
            Edit
          </a>
          <a href="<?= Url::build(['users', 'delete', $user['id']]) ?>">
            Delete
          </a>
        </td>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>

<?php $app->render('layouts/footer') ?>
