<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_id' => 'required|exists:rooms,id',
            'asset_id' => 'nullable|exists:assets,id',
            'issue_description' => 'required|string',
            'status' => 'required|string',
            'technician_id' => 'nullable|exists:users,id',
            'cost' => 'nullable|numeric|min:0',
        ];
    }
}
