<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $description, $status]);
    echo json_encode(['success' => true]);
}
?>
