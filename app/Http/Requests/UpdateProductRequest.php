<?php

namespace App\Http\Requests;

use App\Enums\ProductUnit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'category_id' => ['sometimes', 'required', 'integer', Rule::exists('categories', 'id')->where('user_id', $this->user()->id)],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'sku' => ['sometimes', 'string', 'min:3', 'max:50', Rule::unique('products', 'sku')->where('user_id', $this->user()->id)->ignore($productId)],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'minimum_stock' => ['sometimes', 'required', 'integer', 'min:0'],
            'unit' => ['sometimes', 'required', Rule::enum(ProductUnit::class)],
            'image' => ['sometimes', 'nullable', 'string', 'max:2048'],
        ];
    }
}
