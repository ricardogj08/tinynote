<?php use App\Utils\Url ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= \App\Utils\Html::escape($title) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= Url::base('css/normalize.css') ?>">
  <link rel="stylesheet" href="<?= Url::base('css/terminal.css') ?>">

  <style>
    .text-error {
      color: var(--error-color);
    }

    textarea {
      resize: vertical;
    }
  </style>
</head>
<body>
<main class="container">
