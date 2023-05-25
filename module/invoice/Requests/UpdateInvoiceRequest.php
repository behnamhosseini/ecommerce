<?php


namespace INVOICE\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $invoiceId = $this->route('invoice');

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
