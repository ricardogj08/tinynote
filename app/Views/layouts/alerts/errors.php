<?php if (is_string($errors)): ?>
  <div class="terminal-alert terminal-alert-error">
    <?= \App\Utils\Html::escape($errors) ?>
  </div>
<?php endif ?>
