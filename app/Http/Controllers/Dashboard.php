<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\Board;
use App\Models\Event;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Controller
{
    public function index(Request $request)
    {
        // Set timezone to WIB (Indonesia Bagian Barat)
        Carbon::setLocale('en');
        $now = Carbon::now('Asia/Jakarta');

        // Get selected month from request, default to current month
        $selectedMonth = $request->input('month', $now->month);
        $selectedYear = $request->input('year', $now->year);

        // Format day, date and year
        $currentDateTime = $now->translatedFormat('l, d F Y');

        // Set greeting based on time
        $hour = $now->hour;
        $greeting = '';
        if ($hour >= 5 && $hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($hour >= 12 && $hour < 15) {
            $greeting = 'Good Afternoon';
        } elseif ($hour >= 15 && $hour < 19) {
            $greeting = 'Good Evening';
        } else {
            $greeting = 'Good Night';
        }

        // Create date range for the selected month
        $startOfMonth = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth();

        // Get task stats filtered by month
        $taskStats = [
            'total' => Task::where('user_id', Auth::id())
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count(),
            'todo' => Task::where('user_id', Auth::id())
                ->where('status', 'todo')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count(),
            'in_progress' => Task::where('user_id', Auth::id())
                ->where('status', 'in-progress')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count(),
            'done' => Task::where('user_id', Auth::id())
                ->where('status', 'done')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count(),
        ];

        // Get all stats (no filtering) for comparison
        $allTimeTaskStats = [
            'total' => Task::where('user_id', Auth::id())->count(),
            'todo' => Task::where('user_id', Auth::id())->where('status', 'todo')->count(),
            'in_progress' => Task::where('user_id', Auth::id())->where('status', 'in-progress')->count(),
            'done' => Task::where('user_id', Auth::id())->where('status', 'done')->count(),
        ];

        // Get list of months for dropdown
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = Carbon::createFromDate(null, $i, 1)->format('F');
        }

        // Get current and past 2 years for dropdown
        $years = range($now->year - 2, $now->year);

        $upcomingTasks = Task::where('user_id', Auth::id())->upcoming()->limit(5)->get();
        $boards = Board::withCount('tasks')->where('user_id', Auth::id())->limit(5)->get();
        $todayEvents = Event::today()->where('user_id', Auth::id())->get();
        $upcomingEvents = Event::upcoming()->where('user_id', Auth::id())->limit(5)->get();
        $recentNotes = Note::where('user_id', Auth::id())->latest()->limit(3)->get();

        return view('main.dashboard', compact(
            'greeting',
            'currentDateTime',
            'upcomingTasks',
            'boards',
            'todayEvents',
            'upcomingEvents',
            'recentNotes',
            'taskStats',
            'allTimeTaskStats',
            'months',
            'years',
            'selectedMonth',
            'selectedYear'
        ));
    }
}
