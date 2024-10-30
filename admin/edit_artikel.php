<?php
include '../connect.php'; // pastikan file koneksi menggunakan MySQLi

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->bind_param("i", $id);  // menggunakan bind_param untuk mengikat parameter id
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author_id = $_POST['author_id'];
    $category_id = $_POST['category_id'];
    $image = $_POST['image'];

    $update_stmt = $conn->prepare("UPDATE articles SET title = ?, content = ?, author_id = ?, category_id = ?, image = ? WHERE id = ?");
    $update_stmt->bind_param("ssissi", $title, $content, $author_id, $category_id, $image, $id);
    $update_stmt->execute();

    header("Location: admin_dashboard.php");
    exit();
}

// Ambil data penulis dan kategori untuk dropdown
$authors = $conn->query("SELECT * FROM authors")->fetch_all(MYSQLI_ASSOC);
$categories = $conn->query("SELECT * FROM categories")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Artikel</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="container">
        <h2>Edit Artikel</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($article['title']) ?>" required>
            </div>
            <div class="form-group">
                <label>Konten</label>
                <textarea name="content" class="form-control" required><?= htmlspecialchars($article['content']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Penulis</label>
                <select name="author_id" class="form-control">
                    <?php foreach ($authors as $author): ?>
                        <option value="<?= $author['id'] ?>" <?= $author['id'] == $article['author_id'] ? 'selected' : '' ?>><?= htmlspecialchars($author['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="category_id" class="form-control">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= $category['id'] == $article['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Image URL</label>
                <input type="file" name="image" class="form-control" value="<?= htmlspecialchars($article['image']) ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Artikel</button>
        </form>
    </div>
</body>
</html>
