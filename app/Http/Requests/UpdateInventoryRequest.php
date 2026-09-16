<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\MovementType;

class UpdateInventoryRequest extends FormRequest
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
            'product_id'       => ['sometimes', 'required', 'integer', 'exists:products,id'],
            'user_id'          => ['sometimes','required', 'integer', 'exists:users,id'],
            'type'             => ['sometimes','required', Rule::enum(MovementType::class)],
            'quantity'         => ['sometimes','required', 'integer', 'min:1'],
            'reason'           => ['sometimes','required', 'string', 'max:1000'],
            'before_quantity'  => ['sometimes','required', 'integer', 'min:0'],
            'after_quantity'   => ['sometimes','required', 'integer', 'min:0'],
        ];
    }
}
