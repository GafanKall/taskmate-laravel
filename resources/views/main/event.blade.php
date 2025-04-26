{{-- calendar.blade.php --}}
@extends('layouts.sidebar')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('../css/main/event.css') }}">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.css' rel='stylesheet' />
    <title>TaskMate - Calendar</title>

</head>

<body>
    <header class="header-section">
        <div class="text">Event</div>
        <p>Today, {{ now()->format('l, j F Y') }} </p>
    </header>

    <section class="calendar-section">
        <div id="calendar"></div>

        <!-- Modal Overlay -->
        <div class="modal-overlay" id="modalOverlay"></div>

        <!-- Event Form -->
        <div class="form-container" id="eventForm">
            <div class="form-header">
                <h3 id="formTitle">Add Event</h3>
                <button class="close-btn" id="closeFormBtn"><i class='bx bx-x'></i></button>
            </div>
            <form id="eventCreateForm">
                @csrf
                <input type="hidden" id="eventId" name="eventId" value="">
                <div class="form-group">
                    <label for="eventTitle">Title</label>
                    <input type="text" id="eventTitle" name="title" placeholder="Enter event title" required>
                </div>
                <div class="form-group">
                    <label for="eventDescription">Description</label>
                    <textarea id="eventDescription" name="description" placeholder="Enter event description" rows="3"></textarea>
                </div>
                <div class="form-highlight">
                    <div class="form-group">
                        <label for="eventCategory">Category</label>
                        <select id="eventCategory" name="category">
                            <option value="">-- Select Category --</option>
                            <option value="work">🛠️ Work</option>
                            <option value="personal">👤 Personal</option>
                            <option value="education">📚 Education</option>
                            <option value="health">❤️ Health</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="eventColor">Color</label>
                        <input type="color" id="eventColor" name="color" value="#3788d8">
                    </div>
                </div>
                <div class="form-time">
                    <div class="form-group">
                        <label for="eventStartDate">Start Date</label>
                        <input type="datetime-local" id="eventStartDate" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="eventEndDate">End Date</label>
                        <input type="datetime-local" id="eventEndDate" name="end_date">
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="eventAllDay" name="all_day">
                        All Day Event
                    </label>
                </div>
                <div class="form-actions">
                    <button type="button" class="delete-event-btn" id="deleteEventBtn"
                        style="display: none;">Delete</button>
                    <button type="button" class="cancel-btn" id="cancelBtn">Cancel</button>
                    <button type="submit" class="submit-btn" id="submitEventBtn">Save Event</button>
                </div>
            </form>
        </div>
    </section>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let currentEvent = null;
            let calendar = null;

            // Setup AJAX headers
            function setupAjaxHeaders() {
                return {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                };
            }

            // Initialize calendar
            function initCalendar() {
                const calendarEl = document.getElementById('calendar');
                calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: '/events',
                    selectable: true,
                    select: function(info) {
                        openEventForm(null, info.start, info.end);
                    },
                    eventClick: function(info) {
                        fetchEvent(info.event.id);
                    },
                    eventTimeFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        meridiem: 'short'
                    },
                    eventDidMount: function(info) {
                        // Add category icon based on event's category
                        const categoryIcons = {
                            'work': '🛠️',
                            'personal': '👤',
                            'education': '📚',
                            'health': '❤️'
                        };

                        if (info.event.extendedProps.category && categoryIcons[info.event.extendedProps
                                .category]) {
                            const eventTitle = info.el.querySelector('.fc-event-title');
                            if (eventTitle) {
                                eventTitle.innerHTML =
                                    `${categoryIcons[info.event.extendedProps.category]} ${eventTitle.textContent}`;
                            }
                        }
                    }
                });

                calendar.render();
            }

            // Form elements
            const eventForm = document.getElementById('eventForm');
            const modalOverlay = document.getElementById('modalOverlay');
            const eventCreateForm = document.getElementById('eventCreateForm');
            const formTitle = document.getElementById('formTitle');
            const submitEventBtn = document.getElementById('submitEventBtn');
            const deleteEventBtn = document.getElementById('deleteEventBtn');
            const closeFormBtn = document.getElementById('closeFormBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const eventAllDayCheckbox = document.getElementById('eventAllDay');

            cancelBtn.addEventListener('click', function() {
                hideForm();
            });

            // Show event form
            function openEventForm(event = null, start = null, end = null) {
                resetForm();
                currentEvent = event;

                if (event) {
                    // Edit existing event
                    document.getElementById('eventId').value = event.id;
                    document.getElementById('eventTitle').value = event.title;
                    document.getElementById('eventDescription').value = event.extendedProps.description || '';
                    document.getElementById('eventCategory').value = event.extendedProps.category || '';
                    document.getElementById('eventColor').value = event.backgroundColor || '#3788d8';

                    // Convert to local datetime format for the input
                    if (event.start) {
                        const startDate = new Date(event.start);
                        document.getElementById('eventStartDate').value = formatDateTimeForInput(startDate);
                    }

                    if (event.end) {
                        const endDate = new Date(event.end);
                        document.getElementById('eventEndDate').value = formatDateTimeForInput(endDate);
                    }

                    document.getElementById('eventAllDay').checked = event.allDay;

                    formTitle.textContent = 'Edit Event';
                    submitEventBtn.textContent = 'Update Event';
                    deleteEventBtn.style.display = 'block';
                } else {
                    // Create new event
                    if (start) {
                        document.getElementById('eventStartDate').value = formatDateTimeForInput(start);
                    }

                    if (end) {
                        document.getElementById('eventEndDate').value = formatDateTimeForInput(end);
                    }

                    formTitle.textContent = 'Add Event';
                    submitEventBtn.textContent = 'Save Event';
                    deleteEventBtn.style.display = 'none';
                }

                eventForm.classList.add('show');
                modalOverlay.classList.add('show');
            }

            // Format date for datetime-local input
            function formatDateTimeForInput(date) {
                const d = new Date(date);
                const year = d.getFullYear();
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                const hours = String(d.getHours()).padStart(2, '0');
                const minutes = String(d.getMinutes()).padStart(2, '0');

                return `${year}-${month}-${day}T${hours}:${minutes}`;
            }

            // Hide form
            function hideForm() {
                eventForm.classList.remove('show');
                modalOverlay.classList.remove('show');
            }

            // Reset form fields
            function resetForm() {
                document.getElementById('eventId').value = '';
                eventCreateForm.reset();
            }

            // Fetch event details
            function fetchEvent(eventId) {
                fetch(`/events/${eventId}`, {
                        method: 'GET',
                        headers: setupAjaxHeaders()
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Event not found');
                        }
                        return response.json();
                    })
                    .then(event => {
                        console.log("Fetched event:", event); // Add this for debugging

                        const calendarEvent = {
                            id: event.id,
                            title: event.title,
                            start: event.start_date,
                            end: event.end_date,
                            backgroundColor: event.color,
                            borderColor: event.color,
                            allDay: event.all_day,
                            extendedProps: {
                                description: event.description,
                                category: event.category
                            }
                        };

                        openEventForm(calendarEvent);
                    })
                    .catch(error => console.error('Error fetching event:', error));
            }

            // Handle form submission
            eventCreateForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const eventId = document.getElementById('eventId').value;
                const formData = {
                    title: document.getElementById('eventTitle').value,
                    description: document.getElementById('eventDescription').value,
                    category: document.getElementById('eventCategory').value,
                    color: document.getElementById('eventColor').value,
                    start_date: document.getElementById('eventStartDate').value,
                    end_date: document.getElementById('eventEndDate').value || document.getElementById(
                        'eventStartDate').value,
                    all_day: document.getElementById('eventAllDay').checked
                };

                console.log("Submitting form with ID:", eventId); // Add for debugging
                console.log("Form data:", formData);

                const url = eventId ? `/events/${eventId}` : '/events';
                const method = eventId ? 'PUT' : 'POST';

                fetch(url, {
                        method: method,
                        headers: setupAjaxHeaders(),
                        body: JSON.stringify(formData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log("Response data:", data); // Add for debugging
                        hideForm();
                        calendar.refetchEvents();
                    })
                    .catch(error => {
                        console.error('Error saving event:', error);
                        alert('Failed to save event. Please try again.');
                    });
            });

            // Delete event
            deleteEventBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to delete this event?')) {
                    const eventId = document.getElementById('eventId').value;

                    if (!eventId) {
                        console.error('No event ID to delete');
                        return;
                    }

                    console.log("Deleting event with ID:", eventId); // Add for debugging

                    fetch(`/events/${eventId}`, {
                            method: 'DELETE',
                            headers: setupAjaxHeaders()
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log("Delete response:", data); // Add for debugging
                            hideForm();
                            calendar.refetchEvents();
                        })
                        .catch(error => {
                            console.error('Error deleting event:', error);
                            alert('Failed to delete event. Please try again.');
                        });
                }
            });

            // Initialize calendar
            initCalendar();
        });
    </script>
</body>

</html>
