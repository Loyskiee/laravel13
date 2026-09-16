<?php

namespace App\Http\Requests;

use App\Enums\MovementType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', Rule::exists('products', 'id')->where('user_id', $this->user()->id)],
            'type' => ['required', Rule::enum(MovementType::class)],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'string', 'max:1000'],
            'corrected_quantity' => ['required_if:type,adjustment', 'nullable', 'integer', 'min:0'],
        ];
    }
}
