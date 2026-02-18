<div class="notification-container">
    <div class="notification-icon" id="notificationIcon">
        <i class='bx bx-bell'></i>
        @php
            $unreadCount = \App\Models\Notification::where('user_id', Auth::id())->whereNull('read_at')->count();
        @endphp
        @if($unreadCount > 0)
            <span class="notification-badge">{{ $unreadCount }}</span>
        @endif
    </div>

    <div class="notification-dropdown" id="notificationDropdown">
        <div class="notification-header">
            <h3>Notifications</h3>
            @if($unreadCount > 0)
                <button id="markAllRead">Mark all as read</button>
            @endif
        </div>
        <div class="notification-list">
            @php
                $notifications = \App\Models\Notification::where('user_id', Auth::id())
                    ->latest()
                    ->limit(10)
                    ->get();
            @endphp

            @forelse($notifications as $notification)
                @php
                    $link = '#';
                    if ($notification->task_id && $notification->task) {
                        $link = route('boards.show', $notification->task->board_id);
                    }
                @endphp
                <a href="{{ $link }}" class="notification-item {{ $notification->read_at ? 'read' : 'unread' }} {{ str_starts_with($notification->type, 'deadline') ? 'deadline' : $notification->type }}" style="text-decoration: none; display: block;">
                    <div class="notification-content">
                        <h4>{{ $notification->title }}</h4>
                        <p>{{ $notification->message }}</p>
                        <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                </a>
            @empty
                <div class="notification-empty">
                    <p>No notifications yet</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.notification-container {
    position: relative;
    cursor: pointer;
}

.notification-icon {
    font-size: 24px;
    color: #333;
    position: relative;
    padding: 8px;
    border-radius: 50%;
    transition: background 0.3s;
}

.notification-icon:hover {
    background: #f0f0f0;
}

.notification-badge {
    position: absolute;
    top: 4px;
    right: 4px;
    background: #ff4d4d;
    color: white;
    font-size: 10px;
    padding: 2px 5px;
    border-radius: 10px;
    font-weight: bold;
}

.notification-dropdown {
    position: absolute;
    top: 50px;
    right: 0;
    width: 320px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    display: none;
    z-index: 1000;
    overflow: hidden;
}

.notification-dropdown.active {
    display: block;
}

.notification-header {
    padding: 15px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification-header h3 {
    margin: 0;
    font-size: 16px;
    color: #333;
}

.notification-header button {
    background: none;
    border: none;
    color: #11101d;
    font-size: 12px;
    cursor: pointer;
    font-weight: 500;
}

.notification-list {
    max-height: 400px;
    overflow-y: auto;
}

.notification-item {
    padding: 15px;
    border-bottom: 1px solid #f9f9f9;
    transition: background 0.3s;
}

.notification-item:hover {
    background: #f5f5f5;
}

.notification-item.unread {
    background: #f0f7ff;
}

.notification-item h4 {
    margin: 0 0 5px 0;
    font-size: 14px;
    color: #11101d;
}

.notification-item p {
    margin: 0 0 5px 0;
    font-size: 13px;
    color: #666;
    line-height: 1.4;
}

.notification-time {
    font-size: 11px;
    color: #999;
}

.notification-item.deadline h4 {
    color: #d9534f;
}

.notification-empty {
    padding: 30px;
    text-align: center;
    color: #999;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const icon = document.getElementById('notificationIcon');
    const dropdown = document.getElementById('notificationDropdown');

    icon.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdown.classList.toggle('active');
    });

    document.addEventListener('click', function() {
        dropdown.classList.remove('active');
    });

    dropdown.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    const markBtn = document.getElementById('markAllRead');
    if (markBtn) {
        markBtn.addEventListener('click', function() {
            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(() => location.reload());
        });
    }
});
</script>
