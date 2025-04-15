{{-- health.blade.php --}}
@extends('layouts.sidebar')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('../css/main/health.css') }}">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <title>TaskMate - Health Tasks</title>
</head>
<body>
    <header class="header-section">
        <div class="text">Health Tasks</div>
        <p>Today, {{ now()->format('l, j F Y') }} </p>
    </header>

    <section class="home-section">
        <button class="add-task" id="showFormBtn">Create Task</button>

        <!-- Task List Container -->
        <div class="list-task" id="taskList">
            @php
            $healthTasks = \App\Models\Task::where('user_id', auth()->id())
                ->where('category', 'health')
                ->orderBy('completed')
                ->orderBy('created_at', 'desc')
                ->get();
            @endphp

            @if(count($healthTasks) > 0)
                @foreach ($healthTasks as $task)
                <div class="task" data-task-id="{{ $task->id }}">
                    <div class="content {{ $task->completed ? 'completed' : '' }}">
                        <label class="custom-checkbox">
                            <input type="checkbox" class="task-checkbox" {{ $task->completed ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                        </label>
                        <p class="task-text">{{ $task->title }}</p>
                        <div class="category">❤️ Health</div>
                    </div>
                    <div class="more-btn">
                        <div class="time">
                            <i class='bx bx-time-five'></i>
                            <div class="start-time">{{ $task->start_time ? \Carbon\Carbon::parse($task->start_time)->format('H:i') : '--:--' }}</div>
                            -
                            <div class="end-time">{{ $task->end_time ? \Carbon\Carbon::parse($task->end_time)->format('H:i') : '--:--' }}</div>
                        </div>
                        <div class="task-actions">
                            <button class="edit-task-btn" data-task-id="{{ $task->id }}"><i class='bx bx-edit'></i></button>
                            <button class="delete-task-btn" data-task-id="{{ $task->id }}"><i class='bx bx-trash'></i></button>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-task-message">
                    <p>No health tasks available. Click "Create Task" to add a new health task.</p>
                </div>
            @endif
        </div>

        <!-- Modal Overlay -->
        <div class="modal-overlay" id="modalOverlay"></div>

        <!-- Form Create Task -->
        <div class="form-container" id="taskForm">
            <div class="form-header">
                <h3 id="formTitle">Create New Task</h3>
                <button class="close-btn" id="closeFormBtn"><i class='bx bx-x'></i></button>
            </div>
            <form id="taskCreateForm">
                @csrf
                <input type="hidden" id="taskId" name="taskId" value="">
                <div class="form-group">
                    <label for="taskName">Task</label>
                    <input type="text" id="taskName" name="title" placeholder="Enter task name" required>
                </div>
                <div class="form-group">
                    <label for="taskCategory">Category</label>
                    <select id="taskCategory" name="category" required>
                        <option value="work">🛠️ Work</option>
                        <option value="personal">👤 Personal</option>
                        <option value="education">📚 Education</option>
                        <option value="health" selected>❤️ Health</option>
                    </select>
                </div>
                <div class="form-group time-inputs">
                    <div class="time-input">
                        <label for="startTime">Start Time</label>
                        <input type="time" id="startTime" name="start_time">
                    </div>
                    <div class="time-input">
                        <label for="endTime">End Time</label>
                        <input type="time" id="endTime" name="end_time">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="cancel-btn" id="cancelBtn">Cancel</button>
                    <button type="submit" class="submit-btn" id="submitTaskBtn">Create Task</button>
                </div>
            </form>
        </div>

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
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let taskToDeleteId = null;
        let isEditMode = false;

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
                    const contentElement = taskElement.querySelector('.content');

                    // Toggle completion status via API
                    fetch(`/tasks/${taskId}/toggle-complete`, {
                        method: 'PATCH',
                        headers: setupAjaxHeaders()
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.completed) {
                            contentElement.classList.add('completed');
                            taskElement.style.backgroundColor = '#f9f9f9';
                        } else {
                            contentElement.classList.remove('completed');
                            taskElement.style.backgroundColor = '#ffffff';
                        }
                    })
                    .catch(error => console.error('Error toggling task completion:', error));
                });
            });
        }

        // Form show/hide functionality
        const showFormBtn = document.getElementById('showFormBtn');
        const closeFormBtn = document.getElementById('closeFormBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const taskForm = document.getElementById('taskForm');
        const modalOverlay = document.getElementById('modalOverlay');
        const taskCreateForm = document.getElementById('taskCreateForm');
        const formTitle = document.getElementById('formTitle');
        const submitTaskBtn = document.getElementById('submitTaskBtn');

        // Show form and overlay
        showFormBtn.addEventListener('click', function() {
            // Reset form for creating new task
            resetForm();
            isEditMode = false;
            formTitle.textContent = 'Create New Task';
            submitTaskBtn.textContent = 'Create Task';

            // Pre-select health category
            document.getElementById('taskCategory').value = 'health';

            taskForm.classList.add('show');
            modalOverlay.classList.add('show');
        });

        // Hide form and overlay
        function hideForm() {
            taskForm.classList.remove('show');
            modalOverlay.classList.remove('show');
        }

        closeFormBtn.addEventListener('click', hideForm);
        cancelBtn.addEventListener('click', hideForm);

        // Close form when clicking on the overlay
        modalOverlay.addEventListener('click', hideForm);

        // Reset form fields
        function resetForm() {
            document.getElementById('taskId').value = '';
            taskCreateForm.reset();
        }

        // Handle form submission for create/update
        taskCreateForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const taskId = document.getElementById('taskId').value;
            const formData = {
                title: document.getElementById('taskName').value,
                category: document.getElementById('taskCategory').value,
                start_time: document.getElementById('startTime').value || null,
                end_time: document.getElementById('endTime').value || null
            };

            const url = isEditMode ? `/tasks/${taskId}` : '/tasks';
            const method = isEditMode ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: setupAjaxHeaders(),
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                hideForm();
                // Refresh the page to show updated tasks
                window.location.reload();
            })
            .catch(error => console.error('Error saving task:', error));
        });

        // Edit task functionality
        document.querySelectorAll('.edit-task-btn').forEach(button => {
            button.addEventListener('click', function() {
                const taskId = this.dataset.taskId;

                // Get task data
                fetch(`/tasks/${taskId}`, {
                    method: 'GET',
                    headers: setupAjaxHeaders()
                })
                .then(response => response.json())
                .then(task => {
                    // Populate form with task data
                    document.getElementById('taskId').value = task.id;
                    document.getElementById('taskName').value = task.title;
                    document.getElementById('taskCategory').value = task.category;

                    if (task.start_time) {
                        document.getElementById('startTime').value = task.start_time.substring(0, 5);
                    }

                    if (task.end_time) {
                        document.getElementById('endTime').value = task.end_time.substring(0, 5);
                    }

                    // Set form to edit mode
                    isEditMode = true;
                    formTitle.textContent = 'Edit Task';
                    submitTaskBtn.textContent = 'Update Task';

                    // Show the form
                    taskForm.classList.add('show');
                    modalOverlay.classList.add('show');
                })
                .catch(error => console.error('Error fetching task data:', error));
            });
        });

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
                    // Remove the task element from DOM or reload page
                    window.location.reload();
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
