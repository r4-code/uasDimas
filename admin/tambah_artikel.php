<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author_id = $_POST['author_id'];
    $category_id = $_POST['category_id'];
    $image = $_POST['image'];

    $stmt = $conn->prepare("INSERT INTO articles (title, content, author_id, category_id, image, upload_date, view_count) VALUES (?, ?, ?, ?, ?, CURDATE(), 0)");
    $stmt->bind_param("sssis", $title, $content, $author_id, $category_id, $image);
    $stmt->execute();

    $target_dir = "assets/images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {

        $image = basename($_FILES["image"]["name"]);
    } else {
        $image = "";
    }

    header("Location: admin_dashboard.php");
    exit();
}

$authors = [];
$categories = [];

$author_stmt = $conn->prepare("SELECT * FROM authors");
$author_stmt->execute();
$author_stmt->bind_result($id, $name);
while ($author_stmt->fetch()) {
    $authors[] = ['id' => $id, 'name' => $name];
}
$author_stmt->close();

$category_stmt = $conn->prepare("SELECT * FROM categories");
$category_stmt->execute();
$category_stmt->bind_result($id, $name);
while ($category_stmt->fetch()) {
    $categories[] = ['id' => $id, 'name' => $name];
}
$category_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Artikel</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="container">
        <h2>Tambah Artikel Baru</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Konten</label>
                <textarea name="content" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label>Penulis</label>
                <select name="author_id" class="form-control">
                    <?php foreach ($authors as $author): ?>
                        <option value="<?= $author['id'] ?>"><?= htmlspecialchars($author['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="category_id" class="form-control">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Tambah Artikel</button>
        </form>
    </div>
</body>
</html>
