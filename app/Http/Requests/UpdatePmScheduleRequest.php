<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePmScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'equipment_type' => 'required|string',
            'equipment_id' => 'required|integer',
            'description' => 'required|string|max:255',
            'interval_days' => 'required|integer|min:1',
            'priority' => 'required|in:low,medium,high,critical',
            'estimated_duration' => 'required|integer|min:1',
            'last_performed' => 'nullable|date',
            'next_due' => 'required|date',
        ];
    }
}
