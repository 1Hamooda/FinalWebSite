<?php
require_once 'includes/db.php';
// Fetch all categories
$categories = $conn->query("SELECT * FROM category");
$category_map = [];
while ($cat = $categories->fetch_assoc()) {
    $category_map[$cat['id']] = $cat;
}
// Helper: get category id by name (for static mapping)
function get_category_id($name, $category_map) {
    foreach ($category_map as $id => $cat) {
        if (stripos($cat['name'], $name) !== false) return $id;
    }
    return null;
}
// Fetch latest news for each category (limit as needed)
$news_by_cat = [];
foreach ($category_map as $cat_id => $cat) {
    $news = $conn->query("SELECT * FROM news WHERE category_id=$cat_id AND status='published' ORDER BY dateposted DESC LIMIT 4");
    $news_by_cat[$cat_id] = $news->fetch_all(MYSQLI_ASSOC);
}
// Fetch latest news overall for hero area
$latest_news = $conn->query("SELECT * FROM news WHERE status='published' ORDER BY dateposted DESC LIMIT 3")->fetch_all(MYSQLI_ASSOC);
// Fetch active ads for the front page
$ads = $conn->query("SELECT * FROM advertisements WHERE active=1 AND position='frontpage' ORDER BY id DESC");
$ads_arr = $ads ? $ads->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Document</title>
</head>

<body>
    <nav class="navbar d-flex justify-content-between navbar-expand-lg text-light" style="background-color: #063a6e;">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03"
                aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="#">
                <img class= "navbar-toggler-icon" src="News WebPage/Photos/game.png" alt="">
            </a>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="front-page.php">الرئيسية</a></li>
                    <?php foreach ($category_map as $cat): ?>
                        <li class="nav-item"><a class="nav-link" href="category.php?cat=<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <form class="d-flex me-auto" role="search">
                    <input class="form-control me-2" type="search" placeholder="بحث" aria-label="بحث"
                        style="direction: rtl;">
                </form>
            </div>
            <div class="row d-flex me-auto">
              <div class="row">
                <div class="col">8 °C</div>
                <div class="col">الدوحة</div>
              </div>
            </div>
        </div>
    </nav>

<main class="container mt-4">
<!-- ADS SECTION (top of page, can be moved as needed) -->
<?php if (!empty($ads_arr)): ?>
    <div class="row mb-4">
        <?php foreach ($ads_arr as $ad): ?>
            <div class="col-md-4 mb-2">
                <a href="<?= htmlspecialchars($ad['link']) ?>" target="_blank">
                    <img src="News WebPage/Photos/<?= htmlspecialchars($ad['image_path']) ?>" class="img-fluid rounded shadow" alt="Ad">
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<!-- HERO/MAIN NEWS AREA -->
<section class="row g-4">
    <?php foreach ($latest_news as $i => $news): ?>
        <div class="col-md-<?= $i == 0 ? '4' : '3' ?>">
            <div class="card<?= $i == 0 ? ' bg-dark text-white' : ' mb-3 border-0' ?>">
                <a href="details-page.php?id=<?= $news['id'] ?>">
                    <img src="News WebPage/Photos/<?= htmlspecialchars($news['image']) ?>" class="card-img-top" alt="Image">
                </a>
                <div class="card-body">
                    <h6 class="card-title text-secondary"><?= htmlspecialchars($category_map[$news['category_id']]['name'] ?? '') ?></h6>
                    <p class="card-text<?= $i == 0 ? '' : ' text-black fw-bold' ?>">
                        <?= htmlspecialchars(mb_substr($news['title'], 0, 120)) ?>
                    </p>
                    <?php if ($i == 0): ?>
                        <p class="card-text text-secondary"><?= htmlspecialchars(mb_substr(strip_tags($news['body']), 0, 200)) ?>...</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</section>
<!-- END HERO/MAIN NEWS AREA -->

</section>
<div class="row">
    <span class="col-md border-bottom   ">
        <div class="d-flex justify-content-between mt-3">
            <div class="border-bottom border-3 border-primary pb-1">
                <h5 class="text-secondary">الاكثر قراءة</h5>
            </div>
            <div class="border-bottom border-3 border-primary pb-1">
                <h5>الأكثر تعليقا</h5>
            </div>
            <div>
                
            </div>
        </div>
    </span>
</div>
<section class="row mt-3">
    <div class="col-md-4">
        <ul class="list-unstyled" id="most-read-list">
            <!-- AJAX will fill this -->
        </ul>
    </div>
    <div class="col-md-8">
        <div class="row g-3" id="most-commented-grid">
            <!-- AJAX will fill this -->
        </div>
    </div>
</section>
<script>
function loadMostSections() {
    fetch('includes/most_sections.php')
        .then(r => r.json())
        .then(data => {
            let read = '', comm = '';
            data.mostRead.forEach(n => {
                read += `<li class='border-bottom py-3'>` +
                    `<a class='text-decoration-none text-black' href='details-page.php?id=${n.id}'>` +
                    `<img src='News WebPage/Photos/${n.image}' width='50' class='me-2'>${n.title}</a></li>`;
            });
            data.mostCommented.forEach(n => {
                if (parseInt(n.comment_count) > 0) {
                    comm += `<div class='col-md-6'><div class='card mb-3 border-0'>` +
                        `<a href='details-page.php?id=${n.id}'>` +
                        `<img src='News WebPage/Photos/${n.image}' class='card-img-top' alt='Image'>` +
                        `</a><div class='card-body'>` +
                        `<h6 class='card-title text-secondary'>الأكثر تعليقا</h6>` +
                        `<p class='card-text text-black fw-bold'>${n.title}</p>` +
                        `<span class='badge bg-secondary float-end'>${n.comment_count} تعليقات</span>` +
                        `</div></div></div>`;
                }
            });
            document.getElementById('most-read-list').innerHTML = read;
            document.getElementById('most-commented-grid').innerHTML = comm;
        });
}
loadMostSections();
</script>


<?php
// Render each main section dynamically (Nintendo, Ubisoft, Steam, EA)
$main_sections = ['Nintendo', 'Ubisoft', 'Steam', 'Electronic Arts'];
foreach ($main_sections as $section_name):
    $cat_id = get_category_id($section_name, $category_map);
    if ($cat_id && !empty($news_by_cat[$cat_id])): 
        $section_news = array_slice($news_by_cat[$cat_id], 0, 5); // Only 5 news per section
?>
<section class="row mt-5">
    <div class="row">
        <span class="col-md border-bottom ">
            <div class="d-flex justify-content-between mt-3">
                <h3>
                    <span class="border-bottom border-4 border-primary pb-1 container">
                        <?= htmlspecialchars($category_map[$cat_id]['name']) ?>
                    </span>
                </h3>
                <a class="text-decoration-none" href="category.php?cat=<?= $cat_id ?>">المزيد</a>
            </div>
        </span>
    </div>
    <div class="row g-2">
        <?php
        // First news: main card (col-md-6)
        if (isset($section_news[0])): ?>
            <div class="col-md-6">
                <div class="card-body">
                    <a href="details-page.php?id=<?= $section_news[0]['id'] ?>">
                        <img src="News WebPage/Photos/<?= htmlspecialchars($section_news[0]['image']) ?>" class="card-img-top" alt="Image">
                    </a>
                    <h6 class="card-title text-secondary"><?= htmlspecialchars($category_map[$cat_id]['name']) ?></h6>
                    <p class="card-text text-black">
                        <?= htmlspecialchars(mb_substr($section_news[0]['title'], 0, 80)) ?>
                    </p>
                </div>
            </div>
        <?php endif; ?>
        <?php
        // Next 4 news: 2 per col-md-3 (stacked)
        for ($col = 0; $col < 2; $col++): ?>
            <div class="col-md-3">
                <?php for ($j = 0; $j < 2; $j++):
                    $idx = 1 + $col * 2 + $j;
                    if (isset($section_news[$idx])): ?>
                    <div class="card-body mb-2">
                        <a href="details-page.php?id=<?= $section_news[$idx]['id'] ?>">
                            <img src="News WebPage/Photos/<?= htmlspecialchars($section_news[$idx]['image']) ?>" class="card-img-top" alt="Image">
                        </a>
                        <h6 class="card-title text-secondary"><?= htmlspecialchars($category_map[$cat_id]['name']) ?></h6>
                        <p class="card-text text-black">
                            <?= htmlspecialchars(mb_substr($section_news[$idx]['title'], 0, 80)) ?>
                        </p>
                    </div>
                    <?php endif;
                endfor; ?>
            </div>
        <?php endfor; ?>
    </div>
</section>
<?php endif; endforeach; ?>


</main>

<footer class="bg-secondary text-center text-lg-start text-white mt-4">
    <!-- Grid container -->
    <div class="container p-4">
      <!--Grid row-->
      <div class="row my-4">
        <!--Grid column-->
        <div class="col-lg-3 col-md-6 mb-4 mb-md-0">

          <div class="rounded-circle bg-white shadow-1-strong d-flex align-items-center justify-content-center mb-4 mx-auto" style="width: 150px; height: 150px;">
            <img src="News WebPage\Photos\4533718.png" height="70" alt="" loading="lazy" />
          </div>

          <p class="text-center">The Gaming Comunity</p>
        </div>
        <!--Grid column-->

        <!--Grid column-->
        <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
          <h5 class="text-uppercase mb-4">روابط</h5>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="#" class="text-white"><i class="fas fa-paw pe-3"></i>When your game is missing</a></li>
            <li class="mb-2"><a href="#" class="text-white"><i class="fas fa-paw pe-3"></i>Recently found</a></li>
            <li class="mb-2"><a href="#" class="text-white"><i class="fas fa-paw pe-3"></i>How to buy?</a></li>
            <li class="mb-2"><a href="#" class="text-white"><i class="fas fa-paw pe-3"></i>Game for adoption</a></li>
          </ul>
        </div>
        <!--Grid column-->

        <!--Grid column-->
        <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
          <h5 class="text-uppercase mb-4">عن الموقع</h5>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="#" class="text-white"><i class="fas fa-paw pe-3"></i>General information</a></li>
            <li class="mb-2"><a href="#" class="text-white"><i class="fas fa-paw pe-3"></i>About us</a></li>
            <li class="mb-2"><a href="#" class="text-white"><i class="fas fa-paw pe-3"></i>Statistic data</a></li>
          </ul>
        </div>
        <!--Grid column-->

        <!-- Grid column -->
        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
            <h6 class="text-uppercase mb-4 font-weight-bold">اتصل بنا</h6>
            <i class="bi bi-facebook"></i>
            <i class="bi bi-twitter-x"></i>
            <i class="bi bi-instagram"></i>
            <i class="bi bi-youtube"></i>
        </div>
        <!--Grid column-->
      </div>
      <!--Grid row-->
    </div>
    <!-- Grid container -->
  </footer>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>