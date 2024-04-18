<?php use App\Utils\Url ?>

<div class="terminal-nav">
  <header class="terminal-logo">
    <div class="logo terminal-prompt">
      <a href="<?= Url::build('notes') ?>">
        tinynote
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
