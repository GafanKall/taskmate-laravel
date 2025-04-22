@extends('layouts.sidebar')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('../css/main/boarddetail.css') }}">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <title>{{ $currentBoard->name }} - TaskMate</title>
</head>

<body>
    <header class="header-section">
        <div class="text">TaskMate</div>
        <p>{{ $greeting }}, {{ $currentDateTime }}</p>
    </header>

    <section class="board-detail-section">
        <div class="board-header">
            <h2>{{ $currentBoard->name }}</h2>
            <p class="board-description">{{ $currentBoard->description }}</p>
            <button class="create-task-btn">Add New Task</button>
            <a href="/board" class="back-to-boards-btn"><i class='bx bx-arrow-back'></i> Back to Boards</a>
        </div>

        <div class="kanban-board">
            <!-- To Do Column -->
            <div class="kanban-column">
                <div class="column-header todo">
                    <h3>To Do</h3>
                    <span class="task-count">{{ count($todoTasks) }}</span>
                </div>
                <div class="tasks-container" id="todo-container" data-status="todo">
                    @forelse ($todoTasks as $task)
                        <div class="task-card" data-id="{{ $task->id }}" data-priority="{{ $task->priority }}"
                            draggable="true">
                            <div class="task-header">
                                <span class="task-category">{{ $task->category_emoji }}
                                    {{ $task->category_name }}</span>
                                <div class="task-priority" data-value="{{ $task->priority }}">
                                    @for ($i = 0; $i < 3; $i++)
                                        <div class="priority-dot"></div>
                                    @endfor
                                </div>
                            </div>
                            <div class="task-title">{{ $task->title }}</div>
                            @if ($task->start_date || $task->end_date)
                                <div class="task-dates">
                                    @if ($task->start_date)
                                        <span>Start: {{ date('M d', strtotime($task->start_date)) }}</span>
                                    @endif
                                    @if ($task->end_date)
                                        <span>Due: {{ date('M d', strtotime($task->end_date)) }}</span>
                                    @endif
                                </div>
                            @endif
                            <div class="task-actions">
                                <button class="edit-task-btn" data-id="{{ $task->id }}">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <button class="delete-task-btn" data-id="{{ $task->id }}">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="empty-column-message">No tasks yet</div>
                    @endforelse
                </div>
            </div>

            <!-- In Progress Column -->
            <div class="kanban-column">
                <div class="column-header progress">
                    <h3>In Progress</h3>
                    <span class="task-count">{{ count($inProgressTasks) }}</span>
                </div>
                <div class="tasks-container" id="in-progress-container" data-status="in-progress">
                    @forelse ($inProgressTasks as $task)
                        <div class="task-card" data-id="{{ $task->id }}" data-priority="{{ $task->priority }}"
                            draggable="true">
                            <div class="task-header">
                                <span class="task-category">{{ $task->category_emoji }}
                                    {{ $task->category_name }}</span>
                                <div class="task-priority" data-value="{{ $task->priority }}">
                                    @for ($i = 0; $i < 3; $i++)
                                        <div class="priority-dot"></div>
                                    @endfor
                                </div>
                            </div>
                            <div class="task-title">{{ $task->title }}</div>
                            @if ($task->start_date || $task->end_date)
                                <div class="task-dates">
                                    @if ($task->start_date)
                                        <span>Start: {{ date('M d', strtotime($task->start_date)) }}</span>
                                    @endif
                                    @if ($task->end_date)
                                        <span>Due: {{ date('M d', strtotime($task->end_date)) }}</span>
                                    @endif
                                </div>
                            @endif
                            <div class="task-actions">
                                <button class="edit-task-btn" data-id="{{ $task->id }}">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <button class="delete-task-btn" data-id="{{ $task->id }}">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="empty-column-message">No tasks yet</div>
                    @endforelse
                </div>
            </div>

            <!-- Done Column -->
            <div class="kanban-column">
                <div class="column-header done">
                    <h3>Done</h3>
                    <span class="task-count">{{ count($doneTasks) }}</span>
                </div>
                <div class="tasks-container" id="done-container" data-status="done">
                    @forelse ($doneTasks as $task)
                        <div class="task-card" data-id="{{ $task->id }}" data-priority="{{ $task->priority }}"
                            draggable="true">
                            <div class="task-header">
                                <span class="task-category">{{ $task->category_emoji }}
                                    {{ $task->category_name }}</span>
                                <div class="task-priority" data-value="{{ $task->priority }}">
                                    @for ($i = 0; $i < 3; $i++)
                                        <div class="priority-dot"></div>
                                    @endfor
                                </div>
                            </div>
                            <div class="task-title">{{ $task->title }}</div>
                            @if ($task->start_date || $task->end_date)
                                <div class="task-dates">
                                    @if ($task->start_date)
                                        <span>Start: {{ date('M d', strtotime($task->start_date)) }}</span>
                                    @endif
                                    @if ($task->end_date)
                                        <span>Due: {{ date('M d', strtotime($task->end_date)) }}</span>
                                    @endif
                                </div>
                            @endif
                            <div class="task-actions">
                                <button class="edit-task-btn" data-id="{{ $task->id }}">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <button class="delete-task-btn" data-id="{{ $task->id }}">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="empty-column-message">No tasks yet</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- Task Modal Form -->
    <div class="modal-overlay"></div>
    <div class="form-container" id="taskForm">
        <div class="form-header">
            <h3>Create New Task</h3>
            <button class="close-btn"><i class='bx bx-x'></i></button>
        </div>
        <form id="taskFormData">
            @csrf
            <input type="hidden" id="taskIdInput" name="id" value="">
            <input type="hidden" id="boardIdInput" name="board_id" value="{{ $currentBoard->id }}">

            <div class="form-group">
                <label for="taskTitle">Task Title</label>
                <input type="text" id="taskTitle" name="title" placeholder="Enter task title" required>
            </div>

            <div class="form-group">
                <label for="taskCategory">Category</label>
                <select id="taskCategory" name="category" required>
                    <option value="work">🛠️ Work</option>
                    <option value="personal">👤 Personal</option>
                    <option value="education">📚 Education</option>
                    <option value="health">❤️ Health</option>
                </select>
            </div>

            <div class="form-group">
                <label for="taskPriority">Priority</label>
                <select id="taskPriority" name="priority" required>
                    <option value="0">Low</option>
                    <option value="1">Medium</option>
                    <option value="2">High</option>
                    <option value="3">Urgent</option>
                </select>
            </div>

            <div class="form-group">
                <label for="taskStatus">Status</label>
                <select id="taskStatus" name="status" required>
                    <option value="todo">To Do</option>
                    <option value="in-progress">In Progress</option>
                    <option value="done">Done</option>
                </select>
            </div>

            <div class="form-group">
                <label for="taskStartDate">Start Date (Optional)</label>
                <input type="date" id="taskStartDate" name="start_date">
            </div>

            <div class="form-group">
                <label for="taskEndDate">End Date (Optional)</label>
                <input type="date" id="taskEndDate" name="end_date">
            </div>

            <div class="form-actions">
                <button type="button" class="cancel-btn">Cancel</button>
                <button type="submit" class="submit-btn">Create Task</button>
            </div>
        </form>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-container" id="deleteTaskModal">
        <div class="modal-content">
            <h3>Delete Task</h3>
            <p>Are you sure you want to delete this task?</p>
            <div class="modal-actions">
                <button class="cancel-btn">Cancel</button>
                <button class="delete-confirm-btn">Delete</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const createTaskBtn = document.querySelector('.create-task-btn');
            const modalOverlay = document.querySelector('.modal-overlay');
            const taskForm = document.getElementById('taskForm');
            const taskFormData = document.getElementById('taskFormData');
            const deleteTaskModal = document.getElementById('deleteTaskModal');
            const closeBtns = document.querySelectorAll('.close-btn');
            const cancelBtns = document.querySelectorAll('.cancel-btn');
            const deleteConfirmBtn = document.querySelector('.delete-confirm-btn');
            const taskContainers = document.querySelectorAll('.tasks-container');
            const taskCards = document.querySelectorAll('.task-card');

            let currentTaskToDelete = null;
            let draggedTask = null;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Modal Functions
            function openModal(modal) {
                modalOverlay.style.display = 'block';
                modal.style.display = 'block';
            }

            function closeModal(modal) {
                modalOverlay.style.display = 'none';
                modal.style.display = 'none';
            }

            function closeAllModals() {
                modalOverlay.style.display = 'none';
                taskForm.style.display = 'none';
                deleteTaskModal.style.display = 'none';
            }

            // Function to sort tasks by priority
            function sortTasksByPriority(container) {
                const tasks = Array.from(container.querySelectorAll('.task-card'));

                // If there are no tasks or only 1 task, no need to sort
                if (tasks.length <= 1) return;

                // Remove empty message if exists
                const emptyMessage = container.querySelector('.empty-column-message');
                if (emptyMessage) {
                    emptyMessage.remove();
                }

                // Sort tasks by priority (from high to low)
                tasks.sort((a, b) => {
                    const priorityA = parseInt(a.getAttribute('data-priority'));
                    const priorityB = parseInt(b.getAttribute('data-priority'));
                    return priorityB - priorityA; // Sort from high to low
                });

                // Remove all tasks from container
                tasks.forEach(task => container.removeChild(task));

                // Add sorted tasks back to container
                tasks.forEach(task => {
                    container.appendChild(task);
                });
            }

            // Event Listeners for Modal Controls
            createTaskBtn.addEventListener('click', function() {
                document.querySelector('#taskForm h3').textContent = 'Create New Task';
                document.querySelector('#taskForm .submit-btn').textContent = 'Create Task';
                document.getElementById('taskIdInput').value = '';
                document.getElementById('taskTitle').value = '';
                document.getElementById('taskCategory').value = 'work';
                document.getElementById('taskPriority').value = '0';
                document.getElementById('taskStatus').value = 'todo';
                document.getElementById('taskStartDate').value = '';
                document.getElementById('taskEndDate').value = '';
                openModal(taskForm);
            });

            // Edit task button events
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('edit-task-btn') || e.target.closest('.edit-task-btn')) {
                    const btn = e.target.classList.contains('edit-task-btn') ? e.target : e.target.closest(
                        '.edit-task-btn');
                    const taskId = btn.getAttribute('data-id');

                    // Fetch task data
                    fetch(`/tasks/${taskId}/edit`)
                        .then(response => response.json())
                        .then(task => {
                            document.querySelector('#taskForm h3').textContent = 'Edit Task';
                            document.querySelector('#taskForm .submit-btn').textContent = 'Update Task';
                            document.getElementById('taskIdInput').value = task.id;
                            document.getElementById('taskTitle').value = task.title;
                            document.getElementById('taskCategory').value = task.category;
                            document.getElementById('taskPriority').value = task.priority;
                            document.getElementById('taskStatus').value = task.status;
                            document.getElementById('taskStartDate').value = task.start_date || '';
                            document.getElementById('taskEndDate').value = task.end_date || '';
                            openModal(taskForm);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error fetching task data');
                        });
                }

                if (e.target.classList.contains('delete-task-btn') || e.target.closest(
                        '.delete-task-btn')) {
                    const btn = e.target.classList.contains('delete-task-btn') ? e.target : e.target
                        .closest('.delete-task-btn');
                    currentTaskToDelete = btn.getAttribute('data-id');
                    openModal(deleteTaskModal);
                }
            });

            closeBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    closeAllModals();
                });
            });

            cancelBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    closeAllModals();
                });
            });

            modalOverlay.addEventListener('click', function() {
                closeAllModals();
            });

            // Form submission for tasks
            taskFormData.addEventListener('submit', function(e) {
                e.preventDefault();

                const taskId = document.getElementById('taskIdInput').value;
                const formData = new FormData(this);

                // For PUT requests, Laravel requires _method
                if (taskId) {
                    formData.append('_method', 'PUT');
                }

                fetch(taskId ? `/tasks/${taskId}` : '/tasks', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Server error');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error: ' + error.message);
                    });
            });

            // Delete confirmation
            deleteConfirmBtn.addEventListener('click', function() {
                if (currentTaskToDelete) {
                    fetch(`/tasks/${currentTaskToDelete}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.reload();
                            } else {
                                alert('Error: ' + (data.message || 'Failed to delete task'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while deleting the task');
                        });
                }
                closeAllModals();
            });

            // Drag and Drop Functionality
            taskCards.forEach(card => {
                card.addEventListener('dragstart', function(e) {
                    draggedTask = card;
                    setTimeout(() => {
                        card.classList.add('dragging');
                    }, 0);
                });

                card.addEventListener('dragend', function() {
                    card.classList.remove('dragging');
                    draggedTask = null;
                });
            });

            taskContainers.forEach(container => {
                container.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.classList.add('drag-over');
                });

                container.addEventListener('dragleave', function() {
                    this.classList.remove('drag-over');
                });

                container.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('drag-over');

                    if (draggedTask) {
                        const taskId = draggedTask.getAttribute('data-id');
                        const newStatus = this.getAttribute('data-status');
                        const originalContainer = draggedTask.parentNode;

                        // Check if target container has empty message and remove it
                        const emptyMessage = this.querySelector('.empty-column-message');
                        if (emptyMessage) {
                            emptyMessage.remove();
                        }

                        // Check if source container will be empty after this move
                        if (originalContainer.querySelectorAll('.task-card').length === 1) {
                            // This was the last card, add empty message after removal
                            const newEmptyMessage = document.createElement('div');
                            newEmptyMessage.className = 'empty-column-message';
                            newEmptyMessage.textContent = 'No tasks yet';
                            originalContainer.appendChild(newEmptyMessage);
                        }

                        // Move UI element for fast response
                        this.appendChild(draggedTask);

                        // NEW: Sort tasks after drop
                        sortTasksByPriority(this);

                        // Update task counts
                        updateTaskCounts();

                        // Send update to server
                        const formData = new FormData();
                        formData.append('_method', 'PUT');
                        formData.append('status', newStatus);

                        fetch(`/tasks/${taskId}/status`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                },
                                body: formData
                            })
                            .then(response => {
                                if (!response.ok) {
                                    // If failed, return task to original position
                                    originalContainer.appendChild(draggedTask);

                                    // Sort the original container
                                    sortTasksByPriority(originalContainer);

                                    // Handle empty messages after reverting
                                    const originalEmptyMessage = originalContainer
                                        .querySelector('.empty-column-message');
                                    if (originalEmptyMessage) {
                                        originalEmptyMessage.remove();
                                    }

                                    const currentEmptyMessage = this.querySelector(
                                        '.empty-column-message');
                                    if (this.querySelectorAll('.task-card').length === 0 && !
                                        currentEmptyMessage) {
                                        const newEmptyMessage = document.createElement('div');
                                        newEmptyMessage.className = 'empty-column-message';
                                        newEmptyMessage.textContent = 'No tasks yet';
                                        this.appendChild(newEmptyMessage);
                                    }

                                    updateTaskCounts();
                                    return response.json().then(data => {
                                        throw new Error(data.message || 'Server error');
                                    });
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (!data.success) {
                                    // If unsuccessful, return task to original position
                                    originalContainer.appendChild(draggedTask);

                                    // Sort the original container
                                    sortTasksByPriority(originalContainer);

                                    // Handle empty messages after reverting
                                    const originalEmptyMessage = originalContainer
                                        .querySelector('.empty-column-message');
                                    if (originalEmptyMessage) {
                                        originalEmptyMessage.remove();
                                    }

                                    const currentEmptyMessage = this.querySelector(
                                        '.empty-column-message');
                                    if (this.querySelectorAll('.task-card').length === 0 && !
                                        currentEmptyMessage) {
                                        const newEmptyMessage = document.createElement('div');
                                        newEmptyMessage.className = 'empty-column-message';
                                        newEmptyMessage.textContent = 'No tasks yet';
                                        this.appendChild(newEmptyMessage);
                                    }

                                    updateTaskCounts();
                                    throw new Error(data.message ||
                                        'Failed to update task status');
                                }
                                // Success, no need to do anything as UI is already updated
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error updating task status: ' + error.message);
                            });
                    }
                });
            });

            // Sort tasks in each column when page loads
            taskContainers.forEach(container => {
                sortTasksByPriority(container);
            });

            // Update task counts in column headers
            function updateTaskCounts() {
                const todoCount = document.getElementById('todo-container').querySelectorAll('.task-card').length;
                const inProgressCount = document.getElementById('in-progress-container').querySelectorAll(
                    '.task-card').length;
                const doneCount = document.getElementById('done-container').querySelectorAll('.task-card').length;

                document.querySelector('.column-header.todo .task-count').textContent = todoCount;
                document.querySelector('.column-header.progress .task-count').textContent = inProgressCount;
                document.querySelector('.column-header.done .task-count').textContent = doneCount;
            }
        });

        function attachEventListenersToTask(taskElement) {
            // Add drag event listeners
            taskElement.addEventListener('dragstart', function(e) {
                draggedTask = taskElement;
                setTimeout(() => {
                    taskElement.classList.add('dragging');
                }, 0);
            });

            taskElement.addEventListener('dragend', function() {
                taskElement.classList.remove('dragging');
                draggedTask = null;
            });

            // Add edit and delete button event listeners
            // These will be handled by the document-level event listener due to event delegation
        }
    </script>
</body>

</html>
