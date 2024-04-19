<?php use App\Utils\Url, App\Utils\Html, App\Utils\Env ?>

<div class="terminal-nav">
  <header class="terminal-logo">
    <div class="logo terminal-prompt">
      <a href="<?= Url::build('notes') ?>">
        <?= Html::escape(Env::get('APP_NAME')) ?>
      </a>
    </div>
  </header>
  <nav class="terminal-menu">
    <ul>
      <li>
        <a href="<?= Url::build('notes') ?>">
          Notes
        </a>
      </li>
      <li>
        <a href="<?= Url::build('tags') ?>">
          Tags
        </a>
      </li>
      <li>
        <a href="<?= Url::build('logout') ?>">
          Logout
        </a>
      </li>
    </ul>
  </nav>
</div>
