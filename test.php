<?php
$host = 'localhost';
$dbname = 'blog';
$username = 'root';
$password = '';

// Mengatur pagination
$articlesPerPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$startIndex = ($page - 1) * $articlesPerPage;

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Menghitung total artikel
$totalArticlesStmt = $conn->query("SELECT COUNT(*) FROM articles");
$totalArticles = $totalArticlesStmt->fetchColumn();
$totalPages = ceil($totalArticles / $articlesPerPage);

// Check if a file was uploaded
if (isset($_FILES['article_image']) && $_FILES['article_image']['error'] === UPLOAD_ERR_OK) {
    $image_file = $_FILES['article_image'];
    $image_name = basename($image_file['name']);
    $image_path = "assets/images/" . $image_name;

    // Move the uploaded file to the desired location
    if (move_uploaded_file($image_file['tmp_name'], $image_path)) {
        // The file was uploaded successfully
        $image_url = $image_path;
    } else {
        // There was an error uploading the file
        $image_url = "";
    }
} else {
    $image_url = "";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article_title = $_POST['article_title'];
    $article_content = $_POST['article_content'];
    $article_author = "Your Name"; // Replace with the actual author name
    $article_category = "Uncategorized"; // Replace with the actual category
    $article_upload_date = date('Y-m-d H:i:s');
    $article_view_count = 0;

    $stmt = $conn->prepare("INSERT INTO articles (title, content, author_id, category_id, image, upload_date, view_count) 
                           VALUES (:title, :content, (SELECT id FROM authors WHERE name = :author), 
                           (SELECT id FROM categories WHERE name = :category), :image, :upload_date, :view_count)");
    $stmt->bindParam(':title', $article_title);
    $stmt->bindParam(':content', $article_content);
    $stmt->bindParam(':author', $article_author);
    $stmt->bindParam(':category', $article_category);
    $stmt->bindParam(':image', $image_url);
    $stmt->bindParam(':upload_date', $article_upload_date);
    $stmt->bindParam(':view_count', $article_view_count);
    $stmt->execute();

    // Redirect to the index page after successful submission
    header("Location: index.php");
    exit();
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Final Semester Exam - Dimas Arbi</title>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style-starter.css">
</head>

<body>
    <header class="w3l-header">
        <nav class="navbar navbar-expand-lg navbar-light fill px-lg-0 py-0 px-3">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <span class="fa fa-pencil-square-o"></span> Web Programming Blog</a>
                <button class="navbar-toggler collapsed" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="fa icon-expand fa-bars"></span>
                    <span class="fa icon-close fa-times"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item dropdown @@category__active">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Categories <span class="fa fa-angle-down"></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item @@cp__active" href="technology.php">Technology posts</a>
                                <a class="dropdown-item @@ls__active" href="lifestyle.php">Lifestyle posts</a>
                            </div>
                        </li>
                        <li class="nav-item @@contact__active"><a class="nav-link" href="contact.html">Contact</a></li>
                        <li class="nav-item @@about__active"><a class="nav-link" href="about.html">About</a></li>
                    </ul>
                    <div class="search-right mt-lg-0 mt-2">
                        <a href="#search" title="search"><span class="fa fa-search" aria-hidden="true"></span></a>
                        <div id="search" class="pop-overlay">
                            <div class="popup">
                                <h3 class="hny-title two">Search here</h3>
                                <form action="#" method="Get" class="search-box">
                                    <input type="search" placeholder="Search for blog posts" name="search"
                                        required="required" autofocus="">
                                    <button type="submit" class="btn">Search</button>
                                </form>
                                <a class="close" href="#close">×</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="w3l-homeblock1">
        <div class="container pt-lg-5 pt-md-4">
            <div class="row">
                <div class="col-lg-9 most-recent">
                    <h3 class="section-title-left">Most Recent posts </h3>
                    <div class="list-view">
                        <?php
                        // Query artikel dengan pagination
                        $stmt = $conn->prepare("SELECT a.title, a.content, a.upload_date, a.view_count, a.image, c.name AS category, au.name AS author
                                                FROM articles a
                                                JOIN categories c ON a.category_id = c.id
                                                JOIN authors au ON a.author_id = au.id
                                                ORDER BY a.upload_date DESC
                                                LIMIT :limit OFFSET :offset");
                        $stmt->bindValue(':limit', $articlesPerPage, PDO::PARAM_INT);
                        $stmt->bindValue(':offset', $startIndex, PDO::PARAM_INT);
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<div class="grids5-info img-block-mobile mb-5">';
                            echo '    <div class="blog-info align-self">';
                            echo '        <span class="category">' . htmlspecialchars($row['category']) . '</span>';
                            echo '        <a href="#blog-single" class="blog-desc mt-0">' . htmlspecialchars($row['title']) . '</a>';
                            echo '        <p>' . substr(htmlspecialchars($row['content']), 0, 150) . '...</p>';
                            echo '        <div class="author align-items-center mt-3 mb-1">';
                            echo '            <a href="#author">' . htmlspecialchars($row['author']) . '</a>';
                            echo '        </div>';
                            echo '        <ul class="blog-meta">';
                            echo '            <li class="meta-item blog-lesson">';
                            echo '                <span class="meta-value">' . htmlspecialchars($row['upload_date']) . '</span>';
                            echo '            </li>';
                            echo '            <li class="meta-item blog-students">';
                            echo '                <span class="meta-value"> ' . htmlspecialchars($row['view_count']) . ' views</span>';
                            echo '            </li>';
                            echo '        </ul>';
                            echo '    </div>';
                            if (!empty($row['image'])) {
                                echo '    <img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['title']) . '" class="img-fluid radius-image news-image" style="margin-top:10px;">';
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>

                    <!-- pagination -->
                    <div class="pagination-wrapper mt-5">
                        <ul class="page-pagination">
                            <?php if ($page > 1): ?>
                                <li><a class="next" href="?page=<?php echo $page - 1; ?>"><span class="fa fa-angle-left"></span></a></li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li>
                                    <a class="page-numbers <?php echo ($i == $page) ? 'current' : ''; ?>" href="?page=<?php echo $i; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($page < $totalPages): ?>
                                <li><a class="next" href="?page=<?php echo $page + 1; ?>"><span class="fa fa-angle-right"></span></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 trending mt-lg-0 mt-5 mb-lg-5">
                <div class="pos-sticky">
                    <h3 class="section-title-left">Trending</h3>
                        <?php
                        // Query untuk artikel paling banyak dilihat (trending)
                        $trending_stmt = $conn->prepare("SELECT a.title, a.upload_date, a.view_count, c.name AS category, au.name AS author
                                                        FROM articles a
                                                        JOIN categories c ON a.category_id = c.id
                                                        JOIN authors au ON a.author_id = au.id
                                                        ORDER BY a.view_count DESC
                                                        LIMIT 5"); // Tampilkan 5 artikel trending
                        $trending_stmt->execute();
                        // Variabel penghitung untuk urutan angka
                        $rank = 1;
                        // Tampilkan setiap artikel trending
                        while ($row = $trending_stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<div class="grids5-info">';
                            echo '    <h4>' . $rank . '.</h4>'; // Menampilkan urutan angka
                            echo '    <div class="blog-info">';
                            echo '        <a href="#blog-single" class="blog-desc1">' . htmlspecialchars($row['title']) . '</a>';
                            echo '        <div class="author align-items-center mt-2 mb-1">';
                            echo '            <a href="#author">' . htmlspecialchars($row['author']) . '</a>';
                            echo '        </div>';
                            echo '        <ul class="blog-meta">';
                            echo '            <li class="meta-item blog-lesson">';
                            echo '                <span class="meta-value">' . htmlspecialchars($row['upload_date']) . '</span>';
                            echo '            </li>';
                            echo '            <li class="meta-item blog-students">';
                            echo '                <span class="meta-value"> ' . htmlspecialchars($row['view_count']) . ' views</span>';
                            echo '            </li>';
                            echo '        </ul>';
                            echo '    </div>';
                            echo '</div>';
                            $rank++; // Increment urutan
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="ad-block text-center mt-5">
                <a href="#url"><img src="assets/images/ad.gif" class="img-fluid" alt="ad image" /></a>
            </div>
            <div class="container">
                <h2>Add New Article</h2>
                <form action="?" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="article_title">Article Title</label>
                        <input type="text" class="form-control" id="article_title" name="article_title" required>
                    </div>
                    <div class="form-group">
                        <label for="article_content">Article Content</label>
                        <textarea class="form-control" id="article_content" name="article_content" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="article_image">Article Image</label>
                        <input type="file" class="form-control-file" id="article_image" name="article_image">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <!-- footer -->
    <footer class="w3l-footer-16">
        <div class="footer-content py-lg-5 py-4 text-center">
            <div class="container">
                <div class="copy-right">
                    <h6>© 2024 Web Programming Blog . Made by Dimas Arbi Pramudya with <span class="fa fa-heart" aria-hidden="true"></span><br>Designed by
                        <a href="https://w3layouts.com">W3layouts</a> </h6>
                </div>
                <ul class="author-icons mt-4">
                    <li><a class="facebook" href="#url"><span class="fa fa-facebook" aria-hidden="true"></span></a>
                    </li>
                    <li><a class="twitter" href="#url"><span class="fa fa-twitter" aria-hidden="true"></span></a></li>
                    <li><a class="google" href="#url"><span class="fa fa-google-plus" aria-hidden="true"></span></a>
                    </li>
                    <li><a class="linkedin" href="#url"><span class="fa fa-linkedin" aria-hidden="true"></span></a></li>
                    <li><a class="github" href="#url"><span class="fa fa-github" aria-hidden="true"></span></a></li>
                    <li><a class="dribbble" href="#url"><span class="fa fa-dribbble" aria-hidden="true"></span></a></li>
                </ul>
                <button onclick="topFunction()" id="movetop" title="Go to top">
                    <span class="fa fa-angle-up"></span>
                </button>
            </div>
        </div>

        <!-- move top -->
        <script>
            // When the user scrolls down 20px from the top of the document, show the button
            window.onscroll = function () {
                scrollFunction()
            };

            function scrollFunction() {
                if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                    document.getElementById("movetop").style.display = "block";
                } else {
                    document.getElementById("movetop").style.display = "none";
                }
            }

            // When the user clicks on the button, scroll to the top of the document
            function topFunction() {
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            }
        </script>
        <!-- //move top -->
    </footer>
    <!-- //footer -->

    <!-- Template JavaScript -->
    <script src="assets/js/theme-change.js"></script>

    <script src="assets/js/jquery-3.3.1.min.js"></script>

    <!-- disable body scroll which navbar is in active -->
    <script>
        $(function () {
            $('.navbar-toggler').click(function () {
                $('body').toggleClass('noscroll');
            })
        });
    </script>
    <!-- disable body scroll which navbar is in active -->

    <script src="assets/js/bootstrap.min.js"></script>

</body>

</html>