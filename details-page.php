<?php
require_once 'includes/db.php';
$news_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$news = $conn->query("SELECT * FROM news WHERE id=$news_id AND status='published'")->fetch_assoc();
if (!$news) {
    die('<div class="alert alert-danger">الخبر غير موجود</div>');
}
// Increment view count
$conn->query("UPDATE news SET views = views + 1 WHERE id=$news_id");
// Fetch category
$category = $conn->query("SELECT * FROM category WHERE id=" . intval($news['category_id']))->fetch_assoc();
// Fetch all categories for navbar
$categories = $conn->query("SELECT * FROM category");
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
                <li class="nav-item"><a class="nav-link" href="front-page.php">الرئيسية</a></li>
                <?php while($cat = $categories->fetch_assoc()): ?>
                    <li class="nav-item"><a class="nav-link" href="category.php?cat=<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></a></li>
                <?php endwhile; ?>
            </ul>
            <form class="d-flex me-auto" role="search">
                <input class="form-control me-2" type="search" placeholder="بحث" aria-label="بحث" style="direction: rtl;">
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
<main class="container mt-2">
    <section class="row">
        <div class="col-md-8">
            <span class="text-secondary"> <?= htmlspecialchars($category['name']) ?> </span>
            <h1 class="mt-2"><?= htmlspecialchars($news['title']) ?></h1>
            <span class="mt-2">
                <i class="bi bi-calendar-date"></i>
                <?= htmlspecialchars(date('d-m-Y', strtotime($news['dateposted']))) ?>
            </span>
        </div>
    </section>
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="col-md-12">
                <div class="card border bg-light d-inline-block w-100">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <p class="mb-0 text-dark">شارك القصة</p>
                        <div class="d-flex gap-3">
                            <i class="bi bi-facebook"></i>
                            <i class="bi bi-twitter-x"></i>
                            <i class="bi bi-instagram"></i>
                            <i class="bi bi-youtube"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-0 bg-body-tertiary d-inline-block h-50 ">
                <img class="card-img-top" src="News WebPage/Photos/<?= htmlspecialchars($news['image']) ?>">
                <div class="card-body">
                    <h6 class="text-muted"><?= htmlspecialchars($category['name']) ?></h6>
                    <p class="text-secondary"> <?= nl2br(htmlspecialchars($news['body'])) ?> </p>
                </div>
            </div>
            <!-- Comments Section (AJAX) -->
            <div id="comments-section" class="mt-4">
                <h5>التعليقات</h5>
                <div id="comments-list"></div>
                <form id="comment-form" class="mt-3">
                    <div class="mb-2">
                        <input type="text" name="username" class="form-control" placeholder="اسمك" required>
                    </div>
                    <div class="mb-2">
                        <textarea name="content" class="form-control" placeholder="اكتب تعليقك..." required></textarea>
                    </div>
                    <input type="hidden" name="news_id" value="<?= $news_id ?>">
                    <button type="submit" class="btn btn-primary">إرسال</button>
                </form>
            </div>
            <script>
            function loadComments() {
                fetch('includes/get_comments.php?news_id=<?= $news_id ?>')
                    .then(r => r.json())
                    .then(data => {
                        let html = '';
                        data.forEach(c => {
                            html += `<div class='border rounded p-2 mb-2'><b>${c.username}</b> <span class='text-muted small float-end'>${c.created_at}</span><div>${c.content}</div></div>`;
                        });
                        document.getElementById('comments-list').innerHTML = html;
                    });
            }
            document.getElementById('comment-form').onsubmit = function(e) {
                e.preventDefault();
                fetch('includes/post_comment.php', {
                    method: 'POST',
                    body: new FormData(this)
                }).then(r => r.text()).then(() => {
                    this.reset();
                    loadComments();
                });
            };
            loadComments();
            </script>
        </div>
        <div class="col-md-4">
            <!-- Related news: show 3 other news from same category -->
            <section class="mb-4">
                <h6 class="border-bottom py-3 mt-4">
                    <span class="border-bottom border-4 border-primary">المزيد عن <?= htmlspecialchars($category['name']) ?></span>
                </h6>
                <ul class="list-unstyled mt-2">
                <?php
                $related = $conn->query("SELECT id, title FROM news WHERE category_id=".intval($news['category_id'])." AND id!=$news_id AND status='published' ORDER BY dateposted DESC LIMIT 3");
                while($r = $related->fetch_assoc()): ?>
                    <li class="py-2">◆ <a href="details-page.php?id=<?= $r['id'] ?>" class="text-decoration-none"> <?= htmlspecialchars($r['title']) ?> </a></li>
                <?php endwhile; ?>
                </ul>
            </section>
        </div>
    </div>
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