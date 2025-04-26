@extends('layouts.sidebar')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('../css/main/weekly-schedule.css') }}">
    <title>TaskMate - Weekly Schedule</title>
    <style>

    </style>
</head>

<body>

    <!-- Main Content Area -->
    <div class="content-area">
        <header class="header-section">
            <div class="text">Weekly Schedule</div>
            <p>Today, {{ now()->format('l, d F Y') }}</p>
        </header>

        <section class="schedule-section">
            <div class="schedule-header">
                <h2>Your Schedule</h2>
            </div>

            <div class="schedule-container">
                @foreach ($days as $dayKey => $dayName)
                    <div class="day-card" data-day="{{ $dayKey }}">
                        <div class="day-header">
                            <h3>{{ $dayName }}</h3>
                        </div>
                        <div class="day-content">
                            <div class="schedule-list" id="schedule-{{ $dayKey }}">
                                @if ($schedulesByDay[$dayKey]->count() > 0)
                                    @foreach ($schedulesByDay[$dayKey] as $schedule)
                                        <div class="schedule-item" data-id="{{ $schedule->id }}">
                                            <input type="text" class="schedule-title" value="{{ $schedule->title }}"
                                                data-original="{{ $schedule->title }}">
                                            <div class="schedule-times">
                                                <input type="time" class="time-input start-time"
                                                    value="{{ $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '' }}"
                                                    data-original="{{ $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '' }}">
                                                <input type="time" class="time-input end-time"
                                                    value="{{ $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '' }}"
                                                    data-original="{{ $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '' }}">
                                            </div>
                                            <div class="schedule-actions">
                                                <span class="delete-item"><i class='bx bx-trash'></i></span>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="empty-state">
                                        <i class='bx bx-calendar-x'></i>
                                        <p>No scheduled activities</p>
                                    </div>
                                @endif
                            </div>
                            <div class="add-item-area" id="add-item-{{ $dayKey }}">
                                <i class='bx bx-plus'></i> Add activity
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const addGlobalScheduleBtn = document.getElementById('addGlobalScheduleBtn');
            const dayCards = document.querySelectorAll('.day-card');

            // Setup for each day
            dayCards.forEach(dayCard => {
                const dayIndex = dayCard.getAttribute('data-day');
                const addItemArea = document.getElementById(`add-item-${dayIndex}`);
                const scheduleList = document.getElementById(`schedule-${dayIndex}`);

                // Add new item when clicking the add area
                addItemArea.addEventListener('click', function() {
                    // Remove empty state if it exists
                    const emptyState = scheduleList.querySelector('.empty-state');
                    if (emptyState) {
                        scheduleList.removeChild(emptyState);
                    }

                    // Create new item with input fields
                    const newItem = document.createElement('div');
                    newItem.className = 'schedule-item';
                    newItem.innerHTML = `
                        <input type="text" class="schedule-title" placeholder="Enter activity title..." autofocus>
                        <div class="schedule-times">
                            <input type="time" class="time-input start-time" placeholder="Start time">
                            <input type="time" class="time-input end-time" placeholder="End time">
                        </div>
                        <div class="schedule-actions">
                            <span class="delete-item"><i class='bx bx-trash'></i></span>
                        </div>
                    `;
                    scheduleList.appendChild(newItem);

                    // Focus the title input field
                    const titleInput = newItem.querySelector('.schedule-title');
                    titleInput.focus();

                    // Setup event listeners for the new item
                    setupItemEvents(newItem, dayIndex);
                });

                // Setup existing items
                const existingItems = scheduleList.querySelectorAll('.schedule-item');
                existingItems.forEach(item => {
                    setupItemEvents(item, dayIndex);
                });
            });

            // Global add button
            addGlobalScheduleBtn.addEventListener('click', function() {
                const firstDay = document.querySelector('.day-card');
                if (firstDay) {
                    const dayIndex = firstDay.getAttribute('data-day');
                    const addItemArea = document.getElementById(`add-item-${dayIndex}`);
                    addItemArea.click();
                }
            });

            // Function to setup events for schedule items
            function setupItemEvents(item, dayIndex) {
                const titleInput = item.querySelector('.schedule-title');
                const startTimeInput = item.querySelector('.start-time');
                const endTimeInput = item.querySelector('.end-time');
                const deleteBtn = item.querySelector('.delete-item');
                const itemId = item.getAttribute('data-id');

                // Save on blur events
                titleInput.addEventListener('blur', function() {
                    saveItem(item, dayIndex);
                });

                startTimeInput.addEventListener('change', function() {
                    saveItem(item, dayIndex);
                });

                endTimeInput.addEventListener('change', function() {
                    saveItem(item, dayIndex);
                });

                // Save on Enter key for title
                titleInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        titleInput.blur();
                    }
                });

                // Delete item
                deleteBtn.addEventListener('click', function() {
                    if (itemId) {
                        deleteItem(item, itemId);
                    } else {
                        item.remove();
                    }
                });
            }

            // Function to save item
            function saveItem(item, dayIndex) {
                const titleInput = item.querySelector('.schedule-title');
                const startTimeInput = item.querySelector('.start-time');
                const endTimeInput = item.querySelector('.end-time');

                const title = titleInput.value.trim();
                const startTime = startTimeInput.value;
                const endTime = endTimeInput.value;

                // If title is empty and there's no start time, remove the item
                if (!title && !startTime) {
                    const itemId = item.getAttribute('data-id');
                    if (itemId) {
                        deleteItem(item, itemId);
                    } else {
                        item.remove();
                    }
                    return;
                }

                const itemId = item.getAttribute('data-id');

                // Check if data has changed
                if (itemId &&
                    titleInput.getAttribute('data-original') === title &&
                    startTimeInput.getAttribute('data-original') === startTime &&
                    endTimeInput.getAttribute('data-original') === endTime) {
                    return;
                }

                const formData = new FormData();
                formData.append('_token', csrfToken);
                formData.append('title', title);
                formData.append('day_of_week', dayIndex);

                if (startTime) {
                    formData.append('start_time', startTime);
                }

                if (endTime) {
                    formData.append('end_time', endTime);
                }

                // For existing items
                if (itemId) {
                    formData.append('_method', 'PUT');

                    // Change this in your JavaScript:
                    fetch(`/weekly-schedule/${itemId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                titleInput.setAttribute('data-original', title);
                                startTimeInput.setAttribute('data-original', startTime);
                                endTimeInput.setAttribute('data-original', endTime);
                            } else {
                                alert('Error: ' + (data.message || 'Failed to update schedule'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
                // For new items
                else {
                    fetch('/weekly-schedule', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                item.setAttribute('data-id', data.schedule.id);
                                titleInput.setAttribute('data-original', title);
                                startTimeInput.setAttribute('data-original', startTime);
                                endTimeInput.setAttribute('data-original', endTime);
                            } else {
                                alert('Error: ' + (data.message || 'Failed to create schedule'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
            }

            // Function to delete item
            function deleteItem(item, itemId) {
                fetch(`/weekly-schedule/${itemId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            item.remove();

                            // Check if list is now empty
                            const dayIndex = item.closest('.day-card').getAttribute('data-day');
                            const scheduleList = document.getElementById(`schedule-${dayIndex}`);

                            if (scheduleList.children.length === 0) {
                                // Add empty state
                                const emptyState = document.createElement('div');
                                emptyState.className = 'empty-state';
                                emptyState.innerHTML = `
                                <i class='bx bx-calendar-x'></i>
                                <p>No scheduled activities</p>
                            `;
                                scheduleList.appendChild(emptyState);
                            }
                        } else {
                            alert('Error: ' + (data.message || 'Failed to delete schedule'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        });
    </script>
</body>

</html>
