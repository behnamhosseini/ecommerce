<?php


namespace PRODUCT\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $productId = $this->route('product');

        return [
            'item_name' => 'sometimes|required|string|max:100',
            'active' => 'sometimes|required|boolean',
            'selling_price' => 'sometimes|required|integer',
            'tax' => 'sometimes|numeric|between:0,100',
            'discount_percentage' => 'sometimes|numeric|between:0,100',
            'inventory' => 'sometimes|required|numeric',
        ];
    }
}
