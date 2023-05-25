<?php


namespace PRODUCT\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'item_name' => 'required|string|max:100',
            'active' => 'required|boolean',
            'selling_price' => 'required|integer',
            'tax' => 'numeric|between:0,100',
            'discount_percentage' => 'numeric|between:0,100',
            'inventory' => 'required|numeric',
        ];
    }
}
