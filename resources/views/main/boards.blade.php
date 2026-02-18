<!-- resources/views/main/boards.blade.php -->
@extends('layouts.sidebar')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- My CSS --}}
    <link rel="stylesheet" href="{{ asset('../css/main/board.css') }}">

    {{-- My Icon --}}
    <link rel="icon" type="image/png" href="{{ asset('../images/logo.png') }}">

    {{-- Bx Icon CSS CDN --}}
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <title>TaskMate - Boards</title>
</head>

<body>
    <header class="header-section">
        <div class="header-container">
            <div class="header-left">
                <div class="text">Task Board</div>
                <p>Today, {{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="header-right">
                @include('partials.notification')
            </div>
        </div>
    </header>

    <section class="boards-section">
        <div class="boards-header">
            <h2>Your Boards</h2>
            <button class="create-board-btn">Create Board</button>
        </div>

        <div class="boards-container">
            @forelse ($boards as $board)
                <div class="board-card">
                    <a href="{{ route('boards.show', ['boardId' => $board->id]) }}" class="board-link">
                        <div class="board-title">{{ $board->name }}</div>
                    </a>
                    <div class="board-actions">
                        <button class="edit-board-btn" data-id="{{ $board->id }}" data-name="{{ $board->name }}"
                            data-description="{{ $board->description }}">
                            <i class='bx bx-edit'></i>
                        </button>
                        <button class="delete-board-btn" data-id="{{ $board->id }}">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <p>You don't have any boards yet.</p>
                    <p>Create your first board to get started!</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Board Modal Form -->
    <div class="modal-overlay"></div>
    <div class="form-container" id="boardForm">
        <div class="form-header">
            <h3>Create New Board</h3>
            <button class="close-btn"><i class='bx bx-x'></i></button>
        </div>
        <form id="boardFormData">
            @csrf
            <input type="hidden" id="boardIdInput" name="id" value="">

            <div class="form-group">
                <label for="boardName">Board Name</label>
                <input type="text" id="boardName" name="name" placeholder="Enter board name" required>
            </div>
            <div class="form-group">
                <label for="boardDescription">Description (Optional)</label>
                <textarea id="boardDescription" name="description" placeholder="Enter board description"></textarea>
            </div>
            <div class="form-actions">
                <button type="button" class="cancel-btn">Cancel</button>
                <button type="submit" class="submit-btn">Create Board</button>
            </div>
        </form>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-container" id="deleteModal">
        <div class="modal-content">
            <h3>Delete Board</h3>
            <p>Are you sure you want to delete this board?</p>
            <div class="delete-options">
                <label>
                    <input type="checkbox" id="deleteTasksCheckbox"> Also delete all tasks in this board
                </label>
            </div>
            <div class="modal-actions">
                <button class="cancel-btn">Cancel</button>
                <button class="delete-confirm-btn">Delete</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const createBoardBtn = document.querySelector('.create-board-btn');
            const modalOverlay = document.querySelector('.modal-overlay');
            const boardForm = document.getElementById('boardForm');
            const boardFormData = document.getElementById('boardFormData');
            const deleteModal = document.getElementById('deleteModal');
            const closeBtns = document.querySelectorAll('.close-btn');
            const cancelBtns = document.querySelectorAll('.cancel-btn');
            const deleteConfirmBtn = document.querySelector('.delete-confirm-btn');
            const deleteTasksCheckbox = document.getElementById('deleteTasksCheckbox');

            let currentBoardToDelete = null;
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
                boardForm.style.display = 'none';
                deleteModal.style.display = 'none';
            }

            // Event Listeners for Modal Controls
            createBoardBtn.addEventListener('click', function() {
                document.querySelector('#boardForm h3').textContent = 'Create New Board';
                document.querySelector('#boardForm .submit-btn').textContent = 'Create Board';
                document.getElementById('boardIdInput').value = '';
                document.getElementById('boardName').value = '';
                document.getElementById('boardDescription').value = '';
                openModal(boardForm);
            });

            // Edit board button events
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('edit-board-btn') || e.target.closest('.edit-board-btn')) {
                    const btn = e.target.classList.contains('edit-board-btn') ? e.target : e.target.closest(
                        '.edit-board-btn');
                    const boardId = btn.getAttribute('data-id');
                    const boardName = btn.getAttribute('data-name');
                    const boardDescription = btn.getAttribute('data-description');

                    document.querySelector('#boardForm h3').textContent = 'Edit Board';
                    document.querySelector('#boardForm .submit-btn').textContent = 'Update Board';
                    document.getElementById('boardIdInput').value = boardId;
                    document.getElementById('boardName').value = boardName;
                    document.getElementById('boardDescription').value = boardDescription || '';
                    openModal(boardForm);
                }

                if (e.target.classList.contains('delete-board-btn') || e.target.closest(
                        '.delete-board-btn')) {
                    const btn = e.target.classList.contains('delete-board-btn') ? e.target : e.target
                        .closest('.delete-board-btn');
                    currentBoardToDelete = btn.getAttribute('data-id');
                    deleteTasksCheckbox.checked = false;
                    openModal(deleteModal);
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

            // Replace the board form submission code with this:
            boardFormData.addEventListener('submit', function(e) {
                e.preventDefault();

                // Get values directly to ensure they're properly captured
                const boardId = document.getElementById('boardIdInput').value;
                const boardName = document.getElementById('boardName').value;
                const boardDescription = document.getElementById('boardDescription').value;

                // Check if name exists before submission
                if (!boardName || boardName.trim() === '') {
                    alert('Board name is required');
                    return;
                }

                const submitBtn = this.querySelector('.submit-btn');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Loading...';
                submitBtn.disabled = true;

                // Create form data manually to ensure values are set
                const formData = new FormData();
                formData.append('_token', csrfToken);
                formData.append('name', boardName);
                formData.append('description', boardDescription);

                // For PUT requests, Laravel requires _method
                if (boardId) {
                    formData.append('_method', 'PUT');
                }

                fetch(boardId ? `/boards/${boardId}` : '/boards', {
                        method: 'POST', // Always POST but with _method for PUT
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            // Don't set Content-Type when using FormData
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
                        if (data.success || data.id) {
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error: ' + error.message);
                    })
                    .finally(() => {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    });
            });

            // Delete confirmation
            deleteConfirmBtn.addEventListener('click', function() {
                if (currentBoardToDelete) {
                    const deleteTasks = deleteTasksCheckbox.checked;

                    fetch(`/boards/${currentBoardToDelete}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                delete_tasks: deleteTasks
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.reload();
                            } else {
                                alert('Error: ' + (data.message || 'Failed to delete board'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while deleting the board');
                        });
                }
                closeAllModals();
            });
        });
    </script>
</body>

</html>
