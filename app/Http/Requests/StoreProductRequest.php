<?php

namespace App\Http\Requests;

use App\Enums\ProductUnit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')->where('user_id', $this->user()->id)],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'min:3', 'max:50', Rule::unique('products', 'sku')->where('user_id', $this->user()->id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', Rule::enum(ProductUnit::class)],
            'image' => ['nullable', 'string', 'max:2048'],
        ];
    }
}
