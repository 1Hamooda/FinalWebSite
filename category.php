<?php
require_once 'includes/db.php';
$cat_id = isset($_GET['cat']) ? intval($_GET['cat']) : 0;
$category = $conn->query("SELECT * FROM category WHERE id=$cat_id")->fetch_assoc();
if (!$category) {
    die('<div class="alert alert-danger">القسم غير موجود</div>');
}
$news = $conn->query("SELECT * FROM news WHERE category_id=$cat_id AND status='published' ORDER BY dateposted DESC");
// Fetch all categories for navbar
$categories = $conn->query("SELECT * FROM category");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                    <li class="nav-item"><a class="nav-link" href="front-page.php">الرئيسية</a></li>
                    <?php while($cat = $categories->fetch_assoc()): ?>
                    <li class="nav-item"><a class="nav-link" href="category.php?cat=<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></a></li>
                    <?php endwhile; ?>
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

<main class="m-4">
  <div class="row ">
    <span class="col-md border-bottom ">
        <div>
            <h3>
                <span class="border-bottom border-4 border-primary pb-1 container">
                    <?= htmlspecialchars($category['name']) ?>
                </span>
            </h3>
        </div>
    </span>
</div>

<?php
// Fetch up to 6 news for this category (to match front-page section)
$news_arr = [];
if ($news->num_rows > 0) {
    while($n = $news->fetch_assoc()) {
        $news_arr[] = $n;
        if (count($news_arr) >= 6) break;
    }
}
?>
<section class="row g-2 mt-2">
    <?php if (count($news_arr) > 0): ?>
        <?php foreach ($news_arr as $i => $n): ?>
            <div class="col-md-<?= $i == 0 ? '6' : '3' ?> mb-4">
                <div class="card-body">
                    <a href="details-page.php?id=<?= $n['id'] ?>">
                        <img src="News WebPage/Photos/<?= htmlspecialchars($n['image']) ?>" class="card-img-top" alt="Image">
                    </a>
                    <h6 class="card-title text-secondary"><?= htmlspecialchars($category['name']) ?></h6>
                    <p class="card-text text-black">
                        <?= htmlspecialchars(mb_substr($n['title'], 0, 80)) ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">لا توجد أخبار في هذا القسم بعد.</div>
    <?php endif; ?>
</section>

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