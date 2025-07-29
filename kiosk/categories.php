<?php
session_start();
require_once '../includes/db.php';
require_once '../lang/' . ($_SESSION['lang'] ?? 'en') . '.php';

$section = $_GET['section'] ?? 'wet';
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= $lang['browse_items'] ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      overflow: hidden;
      background-color: #f0f4ff;
    }

    .category-list {
      max-height: 100vh;
      overflow-y: auto;
      background: #e6f0ff;
      padding: 1rem;
      border-right: 1px solid #dee2e6;
    }

    .category-card {
      cursor: pointer;
      transition: 0.2s ease-in-out;
      font-size: 1.2rem;
      border-radius: 10px;
    }

    .category-card:hover {
      background-color: #cfe2ff;
    }

    .category-card.selected {
      background-color: #0d6efd;
      color: white;
    }

    .item-card {
      border-radius: 12px;
      transition: transform 0.2s;
      font-size: 0.85rem;
    }

    .item-card:hover {
      transform: scale(1.02);
    }

    .search-bar {
      margin-bottom: 20px;
    }

    .voice-btn {
      border-radius: 50%;
      width: 40px;
      height: 40px;
      padding: 0;
    }

    .back-btn {
      font-size: 1.2rem;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">

    <!-- Categories Left -->
    <div class="col-md-3 category-list">
      <a href="section.php" class="btn btn-outline-dark w-20 mb-3 back-btn">
        ← <?= $lang['back'] ?? 'Back' ?>
      </a>

      <h5><?= $lang['categories'] ?></h5>
      <div id="categoryAll" class="category-card p-2 mb-2 bg-primary text-white selected" onclick="loadItems('all', this)">
        <?= $lang['all'] ?>
      </div>

      <?php
        $categories = $mysqli->query("SELECT * FROM categories WHERE section = '$section' ORDER BY name_en ASC");
        while ($cat = $categories->fetch_assoc()):
      ?>
        <div class="category-card p-2 mb-2 bg-light d-flex align-items-center" onclick="loadItems(<?= $cat['id'] ?>, this)">
          <img src="../uploads/categories/<?= $cat['image'] ?>" alt="" width="40" height="40" class="me-2">
          <span><?= $_SESSION['lang'] === 'tl' ? $cat['name_tl'] : $cat['name_en'] ?></span>
        </div>
      <?php endwhile; ?>
    </div>

    <!-- Items Right -->
    <div class="col-md-9 p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><?= $lang['items'] ?></h4>
        <div class="search-bar d-flex">
          <input type="text" id="searchInput" class="form-control me-2" placeholder="<?= $lang['search'] ?>" oninput="liveSearch()">
          <button class="btn btn-secondary voice-btn" onclick="startVoice()">
            <i class="bi bi-mic"></i>
          </button>
        </div>
      </div>

      <div id="itemsContainer" class="row g-3">
        <?php
          $items = $mysqli->query("
            SELECT items.*, categories.name_en AS cat_en, categories.name_tl AS cat_tl
            FROM items
            JOIN categories ON items.category_id = categories.id
            WHERE categories.section = '$section'
            ORDER BY items.name_en ASC
          ");
          while ($item = $items->fetch_assoc()):
        ?>
          <div class="col-md-3">
            <div class="card item-card h-100" onclick="location.href='item_detail.php?id=<?= $item['id'] ?>'">
              <img src="../uploads/items/<?= $item['image'] ?>" class="card-img-top" alt="<?= $item['name_en'] ?>">
              <div class="card-body p-2">
                <h6 class="card-title mb-1"><?= $_SESSION['lang'] === 'tl' ? $item['name_tl'] : $item['name_en'] ?></h6>
                <p class="card-text text-muted mb-0"><?= $lang['price'] ?>: ₱<?= number_format($item['price'], 2) ?></p>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>

  </div>
</div>

<script>
function loadItems(categoryId, element) {
  // Reset selected highlight
  document.querySelectorAll('.category-card').forEach(card => {
    card.classList.remove('selected');
    card.classList.remove('text-white');
    card.classList.add('bg-light');
  });

  // Highlight selected card
  element.classList.add('selected');
  element.classList.remove('bg-light');
  element.classList.add('bg-primary', 'text-white');

  const section = '<?= $section ?>';
  fetch(`search.php?category_id=${categoryId}&section=${section}`)
    .then(res => res.text())
    .then(data => {
      document.getElementById('itemsContainer').innerHTML = data;
    });
}

function liveSearch() {
  const keyword = document.getElementById('searchInput').value;
  const section = '<?= $section ?>';
  fetch(`search.php?search=${keyword}&section=${section}`)
    .then(res => res.text())
    .then(data => {
      document.getElementById('itemsContainer').innerHTML = data;
    });
}

function startVoice() {
  const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
  recognition.lang = "<?= $_SESSION['lang'] === 'tl' ? 'fil-PH' : 'en-US' ?>";
  recognition.start();

  recognition.onresult = function(event) {
    const result = event.results[0][0].transcript;
    document.getElementById('searchInput').value = result;
    liveSearch();
  };
}
</script>

</body>
</html>
