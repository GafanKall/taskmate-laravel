<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\Board;
use App\Models\Event;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Controller
{
    public function index()
    {
        // Set timezone to WIB (Indonesia Bagian Barat)
        Carbon::setLocale('en');
        $now = Carbon::now('Asia/Jakarta');

        // Format day, date and year
        $currentDateTime = $now->translatedFormat('l, d F Y');

        // Set greeting based on time
        $hour = $now->hour;
        $greeting = '';
        $currentDateTime = Carbon::now()->format('l, d F Y');
        $upcomingTasks = Task::where('user_id', Auth::id())->upcoming()->limit(5)->get();
        $boards = Board::withCount('tasks')->where('user_id', Auth::id())->limit(5)->get();
        $todayEvents = Event::today()->where('user_id', Auth::id())->get();
        $upcomingEvents = Event::upcoming()->where('user_id', Auth::id())->limit(5)->get();
        $recentNotes = Note::where('user_id', Auth::id())->latest()->limit(3)->get();

        $taskStats = [
            'total' => Task::where('user_id', Auth::id())->count(),
            'todo' => Task::where('user_id', Auth::id())->where('status', 'todo')->count(),
            'in_progress' => Task::where('user_id', Auth::id())->where('status', 'in-progress')->count(),
            'done' => Task::where('user_id', Auth::id())->where('status', 'done')->count(),
        ];

        if ($hour >= 5 && $hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($hour >= 12 && $hour < 15) {
            $greeting = 'Good Afternoon';
        } elseif ($hour >= 15 && $hour < 19) {
            $greeting = 'Good Evening';
        } else {
            $greeting = 'Good Night';
        }

        return view('main.dashboard', compact(
            'greeting',
            'currentDateTime',
            'upcomingTasks',
            'boards',
            'todayEvents',
            'upcomingEvents',
            'recentNotes',
            'taskStats' // Tambahkan variabel taskStats ke compact
        ));
    }
}
