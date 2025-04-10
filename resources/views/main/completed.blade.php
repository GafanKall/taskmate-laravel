@extends('layouts.sidebar')
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('../css/main/home.css') }}">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <title>TaskMate - Completed Tasks</title>
</head>
<body>
    <header class="header-section">
        <div class="text">Completed Tasks</div>
        <p>Today, {{ now()->format('l, d F Y') }} </p>
    </header>
    <section class="home-section">
        <div class="list-task" id="taskList">
            @foreach ($tasks as $task)
            <div class="task" data-task-id="{{ $task->id }}">
                <div class="content completed">
                    <label class="custom-checkbox">
                        <input type="checkbox" class="task-checkbox" checked>
                        <span class="checkmark"></span>
                    </label>
                    <p class="task-text">{{ $task->title }}</p>
                    <div class="category">
                        @if($task->category == 'work')
                            🛠️ Work
                        @elseif($task->category == 'personal')
                            🏠 Personal
                        @elseif($task->category == 'education')
                            📚 Education
                        @elseif($task->category == 'health')
                            ❤️ Health
                        @endif
                    </div>
                </div>
                <div class="more-btn">
                    <div class="time">
                        <i class='bx bx-time-five'></i>
                        <div class="start-time">{{ $task->start_time ? \Carbon\Carbon::parse($task->start_time)->format('H:i') : '--:--' }}</div>
                        -
                        <div class="end-time">{{ $task->end_time ? \Carbon\Carbon::parse($task->end_time)->format('H:i') : '--:--' }}</div>
                    </div>
                    <div class="task-actions">
                        <button class="delete-task-btn" data-task-id="{{ $task->id }}"><i class='bx bx-trash'></i></button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Delete Confirmation Modal -->
    <div class="modal-container" id="deleteModal">
        <div class="modal-content">
            <h3>Delete Task</h3>
            <p>Are you sure you want to delete this task?</p>
            <div class="modal-actions">
                <button class="cancel-btn" id="cancelDeleteBtn">Cancel</button>
                <button class="delete-confirm-btn" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let taskToDeleteId = null;

    // Set up AJAX headers
    function setupAjaxHeaders() {
        return {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        };
    }

    // Checkbox functionality - Toggle task completion
    function setupCheckboxListeners() {
        document.querySelectorAll('.task-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const taskElement = this.closest('.task');
                const taskId = taskElement.dataset.taskId;

                // Toggle completion status via API
                fetch(`/tasks/${taskId}/toggle-complete`, {
                    method: 'PATCH',
                    headers: setupAjaxHeaders()
                })
                .then(response => response.json())
                .then(data => {
                    // If task is uncompleted, remove it from completed tasks view
                    if (!data.completed) {
                        taskElement.remove();
                    }
                })
                .catch(error => console.error('Error toggling task completion:', error));
            });
        });
    }

    // Delete task functionality
    const deleteModal = document.getElementById('deleteModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    document.querySelectorAll('.delete-task-btn').forEach(button => {
        button.addEventListener('click', function() {
            taskToDeleteId = this.dataset.taskId;
            deleteModal.style.display = 'flex';
        });
    });

    cancelDeleteBtn.addEventListener('click', function() {
        deleteModal.style.display = 'none';
    });

    confirmDeleteBtn.addEventListener('click', function() {
        if (taskToDeleteId) {
            fetch(`/tasks/${taskToDeleteId}`, {
                method: 'DELETE',
                headers: setupAjaxHeaders()
            })
            .then(() => {
                deleteModal.style.display = 'none';
                // Remove the task element from DOM
                document.querySelector(`.task[data-task-id="${taskToDeleteId}"]`).remove();
            })
            .catch(error => console.error('Error deleting task:', error));
        }
    });

    // Initialize task checkbox listeners
    setupCheckboxListeners();
});
</script>

</body>
</html>
