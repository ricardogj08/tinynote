<?php if (is_string($error)): ?>
  <div class="terminal-alert terminal-alert-error">
    <?= \App\Utils\Html::escape($error) ?>
  </div>
	<br>
<?php endif ?>
