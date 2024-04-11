<?php if (is_string($success)): ?>
  <div class="terminal-alert terminal-alert-primary">
    <?= \App\Utils\Html::escape($success) ?>
  </div>
<?php endif ?>
