<?php
session_start();
require 'config.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch tasks
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Separate tasks by status
    $todoTasks = array_filter($tasks, function($task) {
        return $task['status'] === 'To Do';
    });

    $inProgressTasks = array_filter($tasks, function($task) {
        return $task['status'] === 'In Progress';
    });

    $doneTasks = array_filter($tasks, function($task) {
        return $task['status'] === 'Done';
    });

    // Function to render tasks
    function renderTasks($taskList) {
        foreach ($taskList as $task) {
            echo '<div class="task">';
            echo '<h4>' . htmlspecialchars($task['title']) . '</h4>';
            echo '<p>' . htmlspecialchars($task['description']) . '</p>';
            echo '<form action="update_task.php" method="POST">';
            echo '<input type="hidden" name="task_id" value="' . $task['id'] . '">';
            echo '<select name="status" onchange="this.form.submit()">';
            echo '<option value="To Do" ' . ($task['status'] === 'To Do' ? 'selected' : '') . '>To Do</option>';
            echo '<option value="In Progress" ' . ($task['status'] === 'In Progress' ? 'selected' : '') . '>In Progress</option>';
            echo '<option value="Done" ' . ($task['status'] === 'Done' ? 'selected' : '') . '>Done</option>';
            echo '</select>';
            echo '</form>';
            echo '<form action="delete_task.php" method="POST" style="display:inline;">';
            echo '<input type="hidden" name="task_id" value="' . $task['id'] . '">';
            echo '<button type="submit">Delete</button>';
            echo '</form>';
            echo '</div>';
        }
    }
}
?>
