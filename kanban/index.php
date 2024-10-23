<?php
session_start();
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

// Handle task creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title'], $_POST['description'])) {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $description, $status]);

    // Refresh the page to show the new task
    header("Location: index.php");
    exit();
}

// Include task fetching logic
include 'get_tasks.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles/newstyle.css">
    <title>Kanban Board</title>
</head>
<body>
    <div class="page-head">
        <img src="favicon.ico" alt="Kanban Board Logo" class="logo" width="50px" height="50px">
        <h2>Kanban Board</h2>
    </div>
    
    <a href="logout.php">Logout</a>
    <div id="kanban-board">
        <div class="column">
            <h3>To Do</h3>
            <form action="index.php" method="POST">
                <input type="text" name="title" placeholder="Task Title" required>
                <input type="text" name="description" placeholder="Task Description" required>
                <input type="hidden" name="status" value="To Do">
                <button type="submit">Add Task</button>
            </form>
            <div class="task-list" id="todo-list">
                <?php renderTasks($todoTasks); ?>
            </div>
        </div>
        <div class="column">
            <h3>In Progress</h3>
            <form action="index.php" method="POST">
                <input type="text" name="title" placeholder="Task Title" required>
                <input type="text" name="description" placeholder="Task Description" required>
                <input type="hidden" name="status" value="In Progress">
                <button type="submit">Add Task</button>
            </form>
            <div class="task-list" id="in-progress-list">
                <?php renderTasks($inProgressTasks); ?>
            </div>
        </div>
        <div class="column">
            <h3>Done</h3>
            <form action="index.php" method="POST">
                <input type="text" name="title" placeholder="Task Title" required>
                <input type="text" name="description" placeholder="Task Description" required>
                <input type="hidden" name="status" value="Done">
                <button type="submit">Add Task</button>
            </form>
            <div class="task-list" id="done-list">
                <?php renderTasks($doneTasks); ?>
            </div>
        </div>
    </div>
</body>
</html>
