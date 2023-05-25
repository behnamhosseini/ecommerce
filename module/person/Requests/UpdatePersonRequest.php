<?php


namespace PERSON\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $personId = $this->route('person');

        return [
            'active' => 'nullable|sometimes|boolean',
            'first_name' => 'nullable|max:50',
            'last_name' => 'nullable|max:50',
            'social_id' => 'nullable|integer|min:1000000000|max:9999999999|unique:people,social_id,' . $personId,
            'birth_date' => 'nullable|date',
            'mobile_number' => 'nullable|numeric|digits_between:11,15|unique:people,mobile_number,' . $personId,
            'mobile_number_description' => 'nullable|max:100',
            'email' => 'nullable|email|unique:people,email,' . $personId,
            'email_description' => 'nullable|max:100',
        ];
    }
}
