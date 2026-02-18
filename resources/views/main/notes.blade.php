@extends('layouts.sidebar')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- My CSS --}}
    <link rel="stylesheet" href="{{ asset('../css/main/notes.css') }}">

    {{-- My Icon --}}
    <link rel="icon" type="image/png" href="{{ asset('../images/logo.png') }}">

    {{-- Bx Icon CSS CDN --}}
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <title>TaskMate - Notes</title>
</head>

<body>
    <header class="header-section">
        <div class="header-container">
            <div class="header-left">
                <div class="text">My Notes</div>
                <p>Today, {{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="header-right">
                @include('partials.notification')
            </div>
        </div>
    </header>

    <section>
        <div class="notes-container">
            <div class="notes-sidebar">
                <div class="sidebar-header">
                    <h3>My Notes</h3>
                    <button class="new-note-btn" id="newNoteBtn"><i class='bx bx-plus'></i> New Note</button>
                </div>
                <div class="notes-list" id="notesList">
                    @if (isset($notes) && count($notes) > 0)
                        @foreach ($notes as $note)
                            <div class="note-item" data-id="{{ $note->id }}">
                                <div class="note-content">
                                    <h3>{{ $note->title }}</h3>
                                    <p>{{ Str::limit(strip_tags($note->content), 50) }}</p>
                                </div>
                                <div class="note-meta">
                                    <small>{{ $note->updated_at->format('M d, Y H:i') }}</small>
                                    <div class="note-actions">
                                        <button class="delete-note-btn" data-id="{{ $note->id }}"><i
                                                class='bx bx-trash'></i></button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-notes-message">
                            <p>No notes available. Click "New Note" to add a new note.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="note-editor" id="noteEditor">
                <div class="editor-header">
                    <input type="text" id="noteTitle" placeholder="Untitled Note" class="note-title-input">
                    <div class="editor-actions">
                        <button id="saveNoteBtn" class="save-btn"><i class='bx bx-save'></i> Save</button>
                    </div>
                </div>
                <div class="editor-body">
                    <textarea id="noteContent" class="simple-editor" placeholder="Write your note here..."></textarea>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal-container" id="deleteModal">
            <div class="modal-content">
                <h3>Delete Note</h3>
                <p>Are you sure you want to delete this note?</p>
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
            let currentNoteId = null;

            // Elements
            const noteEditor = document.getElementById('noteEditor');
            const noteTitleInput = document.getElementById('noteTitle');
            const noteContentInput = document.getElementById('noteContent');
            const saveNoteBtn = document.getElementById('saveNoteBtn');
            const newNoteBtn = document.getElementById('newNoteBtn');

            // Set up AJAX headers
            function setupAjaxHeaders() {
                return {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                };
            }

            // Setup auto-resize for textarea
            function setupTextareaResize() {
                noteContentInput.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });

                // Initial height
                noteContentInput.style.height = 'auto';
                noteContentInput.style.height = (noteContentInput.scrollHeight) + 'px';
            }

            // Initialize a new note
            function startNewNote() {
                currentNoteId = null;
                noteTitleInput.value = '';
                noteContentInput.value = '';

                // Show editor and set focus
                noteEditor.classList.add('active');
                noteTitleInput.focus();

                // Remove active class from all notes
                document.querySelectorAll('.note-item').forEach(item => {
                    item.classList.remove('active');
                });
            }

            // New note button click
            saveNoteBtn.addEventListener('click', function() {
                const title = noteTitleInput.value || 'Untitled Note';
                const content = noteContentInput.value;

                if (currentNoteId) {
                    // Update existing note
                    updateNote(currentNoteId, title, content);
                } else {
                    // Create new note
                    createNote(title, content);
                }
                
            });

            // Function to load notes into the editor
            function loadNote(id) {
                fetch(`/notes/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        currentNoteId = data.id;
                        noteTitleInput.value = data.title;
                        noteContentInput.value = data.content || '';

                        // Update textarea height
                        noteContentInput.style.height = 'auto';
                        noteContentInput.style.height = (noteContentInput.scrollHeight) + 'px';

                        // Show editor if hidden
                        noteEditor.classList.add('active');

                        // Highlight selected note
                        document.querySelectorAll('.note-item').forEach(item => {
                            item.classList.remove('active');
                        });
                        const selectedNote = document.querySelector(`.note-item[data-id="${id}"]`);
                        if (selectedNote) {
                            selectedNote.classList.add('active');
                        }
                    })
                    .catch(error => {
                        console.error('Error loading note:', error);
                        alert('Failed to load note. Please try again.');
                    });
            }

            // Function to create a new note
            function createNote(title, content) {
                fetch('/notes', {
                        method: 'POST',
                        headers: setupAjaxHeaders(),
                        body: JSON.stringify({
                            title,
                            content
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(errorData => {
                                throw new Error(errorData.message || 'Network response was not ok');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Set current note ID to the new note
                        currentNoteId = data.id;

                        // Add the new note to the list
                        const notesList = document.getElementById('notesList');
                        const emptyMessage = notesList.querySelector('.empty-notes-message');
                        if (emptyMessage) {
                            emptyMessage.remove();
                        }

                        const newNoteHtml = `
                            <div class="note-item active" data-id="${data.id}">
                                <div class="note-content">
                                    <h3>${title}</h3>
                                    <p>${content.substring(0, 50)}${content.length > 50 ? '...' : ''}</p>
                                </div>
                                <div class="note-meta">
                                    <small>${new Date().toLocaleString('en-US', {
                                        month: 'short',
                                        day: 'numeric',
                                        year: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    })}</small>
                                    <div class="note-actions">
                                        <button class="delete-note-btn" data-id="${data.id}"><i class='bx bx-trash'></i></button>
                                    </div>
                                </div>
                            </div>
                        `;

                        // Remove active class from all notes
                        document.querySelectorAll('.note-item').forEach(item => {
                            item.classList.remove('active');
                        });

                        // Add new note to the top of the list
                        notesList.insertAdjacentHTML('afterbegin', newNoteHtml);

                        // Setup event listeners for the new note
                        setupNoteItemListeners();
                        setupDeleteButtons();

                        alert('Note created successfully!');
                    })
                    .catch(error => {
                        console.error('Error creating note:', error);
                        alert('Failed to save note. Please try again.');
                    });

            }

            // Function to update an existing note
            function updateNote(id, title, content) {
                fetch(`/notes/${id}`, {
                        method: 'PUT',
                        headers: setupAjaxHeaders(),
                        body: JSON.stringify({
                            title,
                            content
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(errorData => {
                                throw new Error(errorData.message || 'Network response was not ok');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Update the note in the list
                        const noteItem = document.querySelector(`.note-item[data-id="${id}"]`);
                        if (noteItem) {
                            const titleElem = noteItem.querySelector('h3');
                            const contentPreview = noteItem.querySelector('p');
                            const timestamp = noteItem.querySelector('small');

                            titleElem.textContent = title;
                            contentPreview.textContent = content.substring(0, 50) +
                                (content.length > 50 ? '...' : '');

                            const formattedDate = new Date().toLocaleString('en-US', {
                                month: 'short',
                                day: 'numeric',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                            timestamp.textContent = formattedDate;
                        }

                        alert('Note updated successfully!');
                    })
                    .catch(error => {
                        console.error('Error updating note:', error);
                        alert('Failed to update note. Please try again.');
                    });
            }

            // Function to delete a note
            function deleteNote(id) {
                fetch(`/notes/${id}`, {
                        method: 'DELETE',
                        headers: setupAjaxHeaders()
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Remove the note from the list
                        const noteItem = document.querySelector(`.note-item[data-id="${id}"]`);
                        if (noteItem) {
                            noteItem.remove();
                        }

                        // Clear the editor if this was the current note
                        if (currentNoteId === id) {
                            currentNoteId = null;
                            noteTitleInput.value = '';
                            noteContentInput.value = '';
                            noteEditor.classList.remove('active');
                        }

                        // Show "No notes" message if there are no more notes
                        const noteItems = document.querySelectorAll('.note-item');
                        if (noteItems.length === 0) {
                            const notesList = document.getElementById('notesList');
                            notesList.innerHTML =
                                '<div class="empty-notes-message"><p>No notes available. Click "New Note" to add a new note.</p></div>';
                        }

                        // Hide delete modal
                        document.getElementById('deleteModal').style.display = 'none';

                        alert('Note deleted successfully!');
                    })
                    .catch(error => {
                        console.error('Error deleting note:', error);
                        alert('Failed to delete note. Please try again.');
                    });
            }

            // Add event listener for note items to load content
            function setupNoteItemListeners() {
                document.querySelectorAll('.note-item').forEach(item => {
                    item.addEventListener('click', function(e) {
                        // Ignore if clicking on action buttons
                        if (e.target.closest('.note-actions')) {
                            return;
                        }

                        const noteId = this.getAttribute('data-id');
                        loadNote(noteId);
                    });
                });
            }

            // Delete note button functionality
            function setupDeleteButtons() {
                const deleteModal = document.getElementById('deleteModal');
                const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
                const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
                let noteToDeleteId = null;

                document.querySelectorAll('.delete-note-btn').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.stopPropagation(); // Prevent note item click
                        noteToDeleteId = this.getAttribute('data-id');
                        deleteModal.style.display = 'flex';
                    });
                });

                cancelDeleteBtn.addEventListener('click', function() {
                    deleteModal.style.display = 'none';
                });

                confirmDeleteBtn.addEventListener('click', function() {
                    if (noteToDeleteId) {
                        deleteNote(noteToDeleteId);
                    }
                });
            }

            // If there are no notes, show the editor by default
            function checkInitialState() {
                const noteItems = document.querySelectorAll('.note-item');
                if (noteItems.length === 0) {
                    // Start with a new note ready
                    startNewNote();
                } else {
                    // Keep editor hidden until a note is selected
                    noteEditor.classList.remove('active');
                }
            }

            // Initialize all event listeners
            setupNoteItemListeners();
            setupDeleteButtons();
            setupTextareaResize();
            checkInitialState();
        });
    </script>
</body>

</html>
