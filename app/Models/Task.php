<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'board_id',
        'name',
        'priority',
        'status',
        'start_date',
        'end_date',
        'completed'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected $dates = ['start_date', 'end_date'];

    // Remove the appends and the category getter methods

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function getDuration()
    {
        if (!$this->start_date) {
            return 0;
        }

        $start = strtotime($this->start_date);
        $end = $this->end_date ? strtotime($this->end_date) : $start;

        // Calculate days between dates (inclusive)
        return round(($end - $start) / (60 * 60 * 24)) + 1;
    }

    public function isOverdue()
    {
        if (!$this->end_date || $this->status === 'done') {
            return false;
        }

        return strtotime($this->end_date) < strtotime(date('Y-m-d')) && $this->status !== 'done';
    }

    public function getDaysLeft()
    {
        if (!$this->end_date) {
            return null;
        }

        $today = strtotime(date('Y-m-d'));
        $due = strtotime($this->end_date);

        return round(($due - $today) / (60 * 60 * 24));
    }

    public function scopeUpcoming($query)
    {
        return $query->where('end_date', '>=', Carbon::now())
                     ->where('status', '!=', 'done')
                     ->orderBy('end_date', 'asc');
    }
}
