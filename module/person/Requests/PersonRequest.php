<?php


namespace PERSON\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'demonstration_name' => 'required|string|max:100|unique:people,demonstration_name',
            'active' => 'required|boolean',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'social_id' => 'required|integer|min:0|unique:people,social_id',
            'birth_date' => 'required|date',
            'mobile_number' => 'required|string|min:11|max:15',
            'mobile_number_description' => 'required|string|max:100',
            'email' => 'required|email|unique:people,email',
            'email_description' => 'required|string|max:100',
        ];
    }
}
