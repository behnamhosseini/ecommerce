<?php


namespace INVOICE\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'person_id' => 'required|integer|',Rule::exists('people', 'id'),
            'items' => 'required|array',
        ];
    }
}
