<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'part_number' => 'required|string|unique:spare_parts,part_number',
            'stock' => 'required|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'cost' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ];
    }
}
