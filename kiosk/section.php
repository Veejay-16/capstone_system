<?php
session_start();
require_once '../lang/' . ($_SESSION['lang'] ?? 'en') . '.php';
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $lang['select_section'] ?? 'Select Section' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      height: 100vh;
      background: linear-gradient(135deg, #1e3c72, #2a5298);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      overflow: hidden;
    }

    .back-btn {
      position: absolute;
      top: 20px;
      left: 20px;
      z-index: 2;
    }

    .section-title {
      font-size: 2.5rem;
      font-weight: bold;
      margin-bottom: 40px;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
      animation: fadeInDown 1s ease;
    }

    .section-buttons {
      display: flex;
      gap: 30px;
      flex-wrap: wrap;
      justify-content: center;
      animation: fadeInUp 1.2s ease;
    }

    .section-button {
      padding: 20px 60px;
      font-size: 1.8rem;
      font-weight: 600;
      border-radius: 20px;
      border: none;
      cursor: pointer;
      background-color: #00c3ff;
      color: white;
      transition: background-color 0.3s ease, transform 0.2s ease;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      min-width: 220px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .section-button:hover {
      background-color: #00a3d7;
      transform: scale(1.05);
    }

    .section-button i {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }

    .btn-outline-light {
      border-color: #fff;
      color: #fff;
    }

    .btn-outline-light:hover {
      background-color: #fff;
      color: #1e3c72;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>
<body>

<!-- Back Button -->
<a href="index.php" class="btn btn-outline-light back-btn">
  ← <?= $lang['back'] ?? 'Back' ?>
</a>

<!-- Section Title -->
<div class="section-title"><?= $lang['select_section'] ?? 'Select a Section' ?></div>

<!-- Buttons -->
<div class="section-buttons">
  <button class="section-button" onclick="window.location.href='categories.php?section=wet'">
    <i class="bi bi-droplet-half"></i>
    <?= $lang['wet_section'] ?? 'Wet Section' ?>
  </button>
  <button class="section-button" onclick="window.location.href='categories.php?section=dry'">
    <i class="bi bi-box-seam"></i>
    <?= $lang['dry_section'] ?? 'Dry Section' ?>
  </button>
</div>

</body>
</html>
