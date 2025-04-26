<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'color',
        'all_day',
        'category'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'all_day' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeToday($query)
    {
        $today = Carbon::today();
        return $query->whereDate('start_date', $today)
                     ->orderBy('start_date', 'asc');
    }

    /**
     * Scope untuk mendapatkan event yang akan datang
     */
    public function scopeUpcoming($query)
    {
        $tomorrow = Carbon::tomorrow();
        return $query->whereDate('start_date', '>=', $tomorrow)
                     ->orderBy('start_date', 'asc');
    }
}
