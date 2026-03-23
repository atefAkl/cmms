<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'room_layout_id' => 'nullable|exists:room_layouts,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Room name is required.',
            'warehouse_id.required' => 'Warehouse is required.',
            'room_layout_id.required' => 'Room layout is required.',
        ];
    }
}
