# TaskMate

TaskMate is a modern, feature-rich Task Management system built with Laravel. It helps users organize their daily tasks using Kanban-style boards, track deadlines with automated notifications, and manage personal notes and events—all in one place.

## 🚀 Features

### 📊 Dashboard
- **Real-time Statistics**: Insights into total tasks, pending, in-progress, and completed.
- **Monthly Filters**: View your productivity stats by month and year.
- **Recent Activities**: Quick access to upcoming tasks, today's events, and recent notes.

### 📋 Kanban Boards
- **Multiple Boards**: Create separate boards for different projects or areas of life.
- **Drag & Drop Workflow**: Organize tasks into "To Do", "In Progress", and "Done".
- **Priority Levels**: Categorize tasks by priority (Low, Medium, High, Urgent).
- **Time Tracking**: Track "Started" and "Finished" timestamps for every task.

### 🔔 Smart Notifications
- **Deadline Alerts**: Automated notifications triggered at crucial intervals:
    - **24 Hours** before deadline
    - **12 Hours** before deadline
    - **6 Hours** before deadline
- **Direct Redirection**: Click a notification to jump straight to the relevant board.

### 🗓️ Weekly Schedule & Events
- **Visual Schedule**: Organize your week with a clear, time-blocked view.
- **Event Management**: Create and track specific events with categories and times.

### 📝 Personal Notes
- **Lightweight Scratchpad**: Jot down ideas, reminders, or quick notes.
- **Categorized View**: Keep your thoughts organized and easily accessible.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Templates, Vanilla CSS, JavaScript
- **Icons**: [Boxicons](https://boxicons.com/)
- **Database**: MySQL / SQLite
- **Auth**: Laravel Breeze / Fortify (for secure sessions)

---

## 📦 Installation

Follow these steps to set up the project locally:

1. **Clone the repository**
   ```bash
   git clone https://github.com/GafanKall/taskmate-laravel.git
   cd to-do-list
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure Database**
   Update your `.env` file with your database credentials.

5. **Run Migrations**
   ```bash
   php artisan migrate
   ```

6. **Start the Application**
   ```bash
   php artisan serve
   ```

7. **Enable Notifications (Crucial)**
   To ensure deadline-based notifications work, run the scheduler:
   ```bash
   php artisan schedule:work
   ```

---

## 📱 Mobile & Future Integration

Standard Laravel patterns are used throughout, making it ready for:
- **API Development**: Easy integration with Flutter/React Native using **Laravel Sanctum**.
- **JSON Support**: Controllers are optimized for both web and API responses.

## 📄 License

This project is open-source and licensed under the [MIT License](LICENSE).
