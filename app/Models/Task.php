<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'board_id',
        'name',
        'category',
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

    protected $appends = ['category_emoji', 'category_name'];

    public function getCategoryEmojiAttribute()
    {
        switch ($this->category) {
            case 'personal':
                return '👤';
            case 'education':
                return '📚';
            case 'health':
                return '❤️';
            case 'work':
            default:
                return '🛠️';
        }
    }

    public function getCategoryNameAttribute()
    {
        switch ($this->category) {
            case 'personal':
                return 'Personal';
            case 'education':
                return 'Education';
            case 'health':
                return 'Health';
            case 'work':
            default:
                return 'Work';
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
