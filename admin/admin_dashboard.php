<?php
include '../connect.php';

// Hitung jumlah total artikel
$total_stmt = $conn->prepare("SELECT COUNT(*) AS total FROM articles");
$total_stmt->execute();
$total_stmt->bind_result($total);
$total_stmt->fetch();
$total_stmt->close();

// Hitung jumlah artikel per kategori
$category_stmt = $conn->prepare("SELECT c.name AS category, COUNT(a.id) AS count
                                FROM articles a
                                JOIN categories c ON a.category_id = c.id
                                GROUP BY c.name");
$category_stmt->execute();
$category_stmt->bind_result($category_name, $count);
$categories = [];
while ($category_stmt->fetch()) {
    $categories[] = ['category' => $category_name, 'count' => $count];
}
$category_stmt->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="container">
    <h1>Admin Dashboard</h1>
    <div class="summary">
        <h3>Total Artikel: <?= $total ?></h3>
        <div>
            <?php foreach ($categories as $category): ?>
                <p><?= $category['category'] ?>: <?= $category['count'] ?> artikel</p>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="article-management">
        <a href="tambah_artikel.php" class="btn btn-primary">Tambah Artikel</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->prepare("SELECT a.id, a.title, c.name AS category, a.upload_date
                                        FROM articles a
                                        JOIN categories c ON a.category_id = c.id
                                        ORDER BY a.upload_date DESC");
                $stmt->execute();
                $stmt->bind_result($id, $title, $category, $upload_date);
                while ($stmt->fetch()) {
                    echo "<tr>";
                    echo "<td>{$title}</td>";
                    echo "<td>{$category}</td>";
                    echo "<td>{$upload_date}</td>";
                    echo "<td>
                            <a href='edit_artikel.php?id={$id}' class='btn btn-warning'>Edit</a>
                            <a href='hapus_artikel.php?id={$id}' class='btn btn-danger' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                        </td>";
                    echo "</tr>";
                }
                $stmt->close();
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
