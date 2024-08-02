<?php use App\Utils\Html, App\Utils\Url ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= Html::escape($title) ?></title>
  <link rel="icon" type="image/x-icon" href="<?= Url::base('favicon.ico') ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= Url::base('css/normalize.css') ?>">
  <link rel="stylesheet" href="<?= Url::base('css/terminal.css') ?>">

  <style>
    :root {
        --global-font-size: 15px;
        --global-line-height: 1.4em;
        --global-space: 10px;
        --font-stack: Menlo, Monaco, Lucida Console, Liberation Mono,
        DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace,
        serif;
        --mono-font-stack: Menlo, Monaco, Lucida Console, Liberation Mono,
        DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace,
        serif;
        --background-color: #222225;
        --page-width: 60em;
        --font-color: #e8e9ed;
        --invert-font-color: #222225;
        --secondary-color: #a3abba;
        --tertiary-color: #a3abba;
        --primary-color: #62c4ff;
        --error-color: #ff3c74;
        --progress-bar-background: #3f3f44;
        --progress-bar-fill: #62c4ff;
        --code-bg-color: #3f3f44;
        --input-style: solid;
        --display-h1-decoration: none;
    }

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
