<?php
include '../connect.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
$stmt->bind_param("i", $id);  // Menggunakan bind_param untuk mengikat parameter id
$stmt->execute();

header("Location: admin_dashboard.php");
exit();
?>
