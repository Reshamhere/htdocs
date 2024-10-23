document.addEventListener('DOMContentLoaded', () => {
    loadTasks();
});

// Function to load tasks from the server
function loadTasks() {
    fetch('get_tasks.php')
        .then(response => response.json())
        .then(tasks => {
            // Clear existing task lists
            document.getElementById('todo-list').innerHTML = '';
            document.getElementById('in-progress-list').innerHTML = '';
            document.getElementById('done-list').innerHTML = '';

            tasks.forEach(task => {
                const taskElement = document.createElement('div');
                taskElement.innerHTML = `
                    <h4>${task.title}</h4>
                    <p>${task.description}</p>
                    <select onchange="updateTask(${task.id}, this.value)">
                        <option value="To Do" ${task.status === 'To Do' ? 'selected' : ''}>To Do</option>
                        <option value="In Progress" ${task.status === 'In Progress' ? 'selected' : ''}>In Progress</option>
                        <option value="Done" ${task.status === 'Done' ? 'selected' : ''}>Done</option>
                    </select>
                    <button onclick="deleteTask(${task.id})">Delete</button>
                `;
                document.getElementById(task.status.toLowerCase().replace(' ', '-') + '-list').appendChild(taskElement);
            });
        });
}

// Function to add a new task
function addTask(status) {
    const title = prompt("Enter task title:");
    const description = prompt("Enter task description:");

    if (title && description) {
        fetch('create_task.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `title=${encodeURIComponent(title)}&description=${encodeURIComponent(description)}&status=${status}`,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadTasks(); // Reload tasks after adding
            }
        });
    }
}

// Function to update a task
function updateTask(taskId, status) {
    const title = prompt("Update task title:", "");
    const description = prompt("Update task description:", "");

    if (title && description) {
        fetch('update_task.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `task_id=${taskId}&title=${encodeURIComponent(title)}&description=${encodeURIComponent(description)}&status=${status}`,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadTasks(); // Reload tasks after updating
            }
        });
    }
}

// Function to delete a task
function deleteTask(taskId) {
    if (confirm("Are you sure you want to delete this task?")) {
        fetch('delete_task.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `task_id=${taskId}`,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadTasks(); // Reload tasks after deletion
            }
        });
    }
}
