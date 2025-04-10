<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Home extends Controller
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

        if ($hour >= 5 && $hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($hour >= 12 && $hour < 15) {
            $greeting = 'Good Afternoon';
        } elseif ($hour >= 15 && $hour < 19) {
            $greeting = 'Good Evening';
        } else {
            $greeting = 'Good Night';
        }

        // Get user's tasks
        $tasks = Task::where('user_id', Auth::id())
                      ->orderBy('completed')
                      ->orderBy('start_time')
                      ->get();

        return view('main.home', compact('currentDateTime', 'greeting', 'tasks'));
    }
}
