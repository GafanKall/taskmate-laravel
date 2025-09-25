{{-- dashboard.blade.php --}}
@extends('layouts.sidebar')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- My Icon --}}
    <link rel="icon" type="image/png" href="{{ asset('../images/logo.png') }}">

    {{-- My CSS --}}
    <link rel="stylesheet" href="{{ asset('../css/main/dashboard.css') }}">

    {{-- Bx Icon CSS CDN --}}
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <title>TaskMate - Dashboard</title>
</head>

<body>
    <header class="header-section">
        <div class="text">{{ $greeting }}, {{ Auth::user()->name }}!👋</div>
        <p>Today, {{ $currentDateTime }} </p>
    </header>

    <section class="dashboard-section">
        <!-- Month Filter -->
        <div class="month-filter-container">
            <form id="monthFilterForm" action="{{ route('dashboard') }}" method="GET" class="month-filter-form">
                <div class="filter-group">
                    <label for="month">Month:</label>
                    <select name="month" id="month" onchange="this.form.submit()">
                        @foreach ($months as $key => $monthName)
                            <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>
                                {{ $monthName }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </form>
        </div>

        <!-- Stats Overview -->
        <div class="stats-container">
            <div class="stat-card total">
                <div class="stat-icon"><i class='bx bx-task'></i></div>
                <div class="stat-info">
                    <h3>{{ $taskStats['total'] }}</h3>
                    <p>Total Tasks <span
                            class="filter-indication">({{ Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('M Y') }})</span>
                    </p>
                </div>
            </div>
            <div class="stat-card todo">
                <div class="stat-icon"><i class='bx bx-clipboard'></i></div>
                <div class="stat-info">
                    <h3>{{ $taskStats['todo'] }}</h3>
                    <p>To Do <span
                            class="filter-indication">({{ Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('M Y') }})</span>
                    </p>
                </div>
            </div>
            <div class="stat-card progress">
                <div class="stat-icon"><i class='bx bx-loader-circle'></i></div>
                <div class="stat-info">
                    <h3>{{ $taskStats['in_progress'] }}</h3>
                    <p>In Progress <span
                            class="filter-indication">({{ Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('M Y') }})</span>
                    </p>
                </div>
            </div>
            <div class="stat-card done">
                <div class="stat-icon"><i class='bx bx-check-circle'></i></div>
                <div class="stat-info">
                    <h3>{{ $taskStats['done'] }}</h3>
                    <p>Completed <span
                            class="filter-indication">({{ Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('M Y') }})</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Content -->
        <div class="dashboard-content">
            <!-- Left Column -->
            <div class="dashboard-column">
                <!-- Upcoming Tasks -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class='bx bx-calendar-check'></i> Upcoming Tasks</h3>
                        <a href="/board" class="view-all">View All <i class='bx bx-right-arrow-alt'></i></a>
                    </div>
                    <div class="card-content">
                        @if (count($upcomingTasks) > 0)
                            <ul class="task-list">
                                @foreach ($upcomingTasks as $task)
                                    <li class="task-item priority-{{ $task->priority }}">
                                        <div class="task-details">
                                            <h4>{{ $task->title }}</h4>
                                            <div class="task-meta">
                                                <span class="task-date">
                                                    <i class='bx bx-calendar'></i>
                                                    Due: {{ \Carbon\Carbon::parse($task->end_date)->format('M d') }}
                                                </span>
                                                <span class="task-status status-{{ $task->status }}">
                                                    {{ ucfirst(str_replace('-', ' ', $task->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="empty-message">No upcoming tasks.</p>
                        @endif
                    </div>
                </div>

                <!-- My Boards -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class='bx bx-grid-alt'></i> My Boards</h3>
                        <a href="/board" class="view-all">View All <i class='bx bx-right-arrow-alt'></i></a>
                    </div>
                    <div class="card-content">
                        @if (count($boards) > 0)
                            <ul class="board-list">
                                @foreach ($boards as $board)
                                    <li class="board-item">
                                        <a href="/board/{{ $board->id }}">
                                            <div class="board-info">
                                                <h4>{{ $board->name }}</h4>
                                                <span>{{ $board->tasks_count }} tasks</span>
                                            </div>
                                            <i class='bx bx-chevron-right'></i>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="empty-message">No boards available.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="dashboard-column">
                <!-- Today's Events -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class='bx bx-calendar-event'></i> Today's Events</h3>
                        <a href="/event" class="view-all">Event <i class='bx bx-right-arrow-alt'></i></a>
                    </div>
                    <div class="card-content">
                        @if (count($todayEvents) > 0)
                            <ul class="event-list">
                                @foreach ($todayEvents as $event)
                                    <li class="event-item" style="border-left-color: {{ $event->color }};">
                                        <div class="event-time">
                                            @if ($event->all_day)
                                                <span>All day</span>
                                            @else
                                                <span>{{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}</span>
                                            @endif
                                        </div>
                                        <div class="event-details">
                                            <h4>{{ $event->title }}</h4>
                                            @if ($event->category)
                                                <div class="event-category">
                                                    @switch($event->category)
                                                        @case('work')
                                                            <span>🛠️ Work</span>
                                                        @break

                                                        @case('personal')
                                                            <span>👤 Personal</span>
                                                        @break

                                                        @case('education')
                                                            <span>📚 Education</span>
                                                        @break

                                                        @case('health')
                                                            <span>❤️ Health</span>
                                                        @break

                                                        @default
                                                            <span>{{ ucfirst($event->category) }}</span>
                                                    @endswitch
                                                </div>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="empty-message">No events scheduled for today.</p>
                        @endif
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class='bx bx-calendar-alt'></i> Upcoming Events</h3>
                    </div>
                    <div class="card-content">
                        @if (count($upcomingEvents) > 0)
                            <ul class="event-list">
                                @foreach ($upcomingEvents as $event)
                                    <li class="event-item" style="border-left-color: {{ $event->color }};">
                                        <div class="event-time">
                                            <span
                                                class="event-date">{{ \Carbon\Carbon::parse($event->start_date)->format('M d') }}</span>
                                        </div>
                                        <div class="event-details">
                                            <h4>{{ $event->title }}</h4>
                                            @if ($event->category)
                                                <div class="event-category">
                                                    @switch($event->category)
                                                        @case('work')
                                                            <span>🛠️ Work</span>
                                                        @break

                                                        @case('personal')
                                                            <span>👤 Personal</span>
                                                        @break

                                                        @case('education')
                                                            <span>📚 Education</span>
                                                        @break

                                                        @case('health')
                                                            <span>❤️ Health</span>
                                                        @break

                                                        @default
                                                            <span>{{ ucfirst($event->category) }}</span>
                                                    @endswitch
                                                </div>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="empty-message">No upcoming events.</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Notes -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class='bx bx-notepad'></i> Recent Notes</h3>
                        <a href="/notes" class="view-all">View All <i class='bx bx-right-arrow-alt'></i></a>
                    </div>
                    <div class="card-content">
                        @if (count($recentNotes) > 0)
                            <ul class="notes-list">
                                @foreach ($recentNotes as $note)
                                    <li class="note-item">
                                        <a href="/notes" onclick="loadNote('{{ $note->id }}')">
                                            <h4>{{ $note->title }}</h4>
                                            <p>{{ Str::limit(strip_tags($note->content), 60) }}</p>
                                            <span class="note-date">{{ $note->updated_at->diffForHumans() }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="empty-message">No notes available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function loadNote(noteId) {
            // Store note ID in session storage to open it when redirected to notes page
            sessionStorage.setItem('openNoteId', noteId);
        }

        // Reset filter script
        document.addEventListener('DOMContentLoaded', function() {
            const resetBtn = document.getElementById('resetFilter');
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    window.location.href = "{{ route('dashboard') }}";
                });
            }
        });
    </script>
</body>

</html>
