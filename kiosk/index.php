<?php
// Set default language
session_start();
if (!isset($_SESSION['lang'])) {
  $_SESSION['lang'] = 'en'; // default to English
}

// Language switcher
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'tl'])) {
  $_SESSION['lang'] = $_GET['lang'];
  header("Location: index.php");
  exit;
}

require_once '../lang/' . $_SESSION['lang'] . '.php';
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $lang['welcome_title'] ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      height: 100vh;
      margin: 0;
      background: linear-gradient(135deg, #1e3c72, #2a5298);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      color: #fff;
    }

    .language-switch {
      position: absolute;
      top: 20px;
      right: 20px;
      z-index: 2;
    }

    .welcome-box {
      background: rgba(255, 255, 255, 0.1);
      padding: 60px 40px;
      border-radius: 20px;
      backdrop-filter: blur(10px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
      max-width: 500px;
      width: 90%;
      animation: fadeIn 1s ease-in-out;
    }

    .title {
      font-size: 2.8rem;
      font-weight: bold;
      margin-bottom: 10px;
      color: #fff;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
    }

    .subtitle {
      font-size: 1.3rem;
      margin-bottom: 40px;
      color: #e0e0e0;
    }

    .tap-start {
      font-size: 1.6rem;
      font-weight: 600;
      padding: 15px 35px;
      border-radius: 12px;
      border: none;
      background-color: #00c3ff;
      color: #fff;
      animation: pulse 2s infinite;
      transition: background-color 0.3s ease;
    }

    .tap-start:hover {
      background-color: #00a3d7;
    }

    @keyframes pulse {
      0% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.05); opacity: 0.9; }
      100% { transform: scale(1); opacity: 1; }
    }

    @keyframes fadeIn {
      0% { opacity: 0; transform: translateY(30px); }
      100% { opacity: 1; transform: translateY(0); }
    }

    .btn-outline-light.active {
      background-color: #fff !important;
      color: #1e3c72 !important;
    }
  </style>
</head>
<body>

<!-- Language Switcher -->
<div class="language-switch">
  <a href="?lang=en" class="btn btn-outline-light btn-sm <?= $_SESSION['lang'] === 'en' ? 'active' : '' ?>">English</a>
  <a href="?lang=tl" class="btn btn-outline-light btn-sm <?= $_SESSION['lang'] === 'tl' ? 'active' : '' ?>">Tagalog</a>
</div>

<!-- Welcome Box -->
<div class="welcome-box">
  <div class="title">Nasugbu Public Market</div>
  <div class="subtitle"><?= $lang['welcome_title'] ?></div>
  <button class="tap-start" onclick="window.location.href='section.php'">
    <?= $lang['tap_to_start'] ?>
  </button>
</div>

</body>
</html>
