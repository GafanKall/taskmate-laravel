<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckTaskDeadlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:check-deadlines';

    protected $description = 'Check for tasks due within 24 hours and create notifications every 6 hours';

    public function handle()
    {
        $now = \Carbon\Carbon::now();
        
        $tasks = \App\Models\Task::where('status', '!=', 'done')
            ->whereNotNull('end_date')
            ->where('end_date', '>', $now)
            ->get();

        $count = 0;
        foreach ($tasks as $task) {
            $hoursUntil = $now->diffInHours($task->end_date, false);
            
            // Define intervals the user wants: 24, 12, 6
            $intervals = [24, 12, 6];
            
            foreach ($intervals as $interval) {
                // If we are within the interval window (e.g., between 24 and 23.5 hours)
                // and we haven't notified for this specific interval yet.
                // To keep it simple, we'll check if a notification for this task and interval exists.
                
                if ($hoursUntil <= $interval && $hoursUntil > ($interval - 3)) { // 3-hour window to catch it
                    $exists = \App\Models\Notification::where('task_id', $task->id)
                        ->where('type', 'deadline_' . $interval)
                        ->exists();

                    if (!$exists) {
                        \App\Models\Notification::create([
                            'user_id' => $task->user_id,
                            'task_id' => $task->id,
                            'title' => 'Deadline Approaching',
                            'message' => "Your task \"{$task->title}\" is due in {$interval} hours!",
                            'type' => 'deadline_' . $interval,
                        ]);

                        $task->update(['last_notified_at' => $now]);
                        $count++;
                        break; // Only one notification per run for a task
                    }
                }
            }
        }

        $this->info("Created {$count} notifications for specific deadline intervals.");
    }
}
