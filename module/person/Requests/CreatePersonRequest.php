<?php


namespace PERSON\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePersonRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'active' => 'required|boolean',
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'social_id' => 'required|integer|min:1000000000|max:9999999999|unique:people',
            'birth_date' => 'required|date',
            'mobile_number' => 'required|numeric|digits_between:11,15|unique:people',
            'mobile_number_description' => 'nullable|max:100',
            'email' => 'nullable|email|unique:people',
            'email_description' => 'nullable|max:100',
        ];
    }
}
