<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'];
    $status = $_POST['status']; // Get the new status from the form

    $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    $stmt->execute([$status, $task_id]);

    // Redirect back to the index page
    header("Location: index.php");
    exit();
}
?>
