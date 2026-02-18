<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'board_id' => 'required|exists:boards,id',
            'title' => 'required|string|max:255',
            'priority' => 'nullable|string|in:low,medium,high,urgent',
            'status' => 'nullable|string|in:todo,in_progress,done',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'completed' => 'nullable|boolean',
        ];
    }
}
