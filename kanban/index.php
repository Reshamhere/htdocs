<?php
session_start();
require 'config.php';

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
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Kanban Board</title>
</head>
<body>
    <h2>Kanban Board</h2>
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
                <?php include 'get_tasks.php'; renderTasks($todoTasks); ?>
            </div>
        </div>
        <div class="column">
            <h3>In Progress</h3>
            <div class="task-list" id="in-progress-list">
                <?php renderTasks($inProgressTasks); ?>
            </div>
        </div>
        <div class="column">
            <h3>Done</h3>
            <div class="task-list" id="done-list">
                <?php renderTasks($doneTasks); ?>
            </div>
        </div>
    </div>
</body>
</html>


