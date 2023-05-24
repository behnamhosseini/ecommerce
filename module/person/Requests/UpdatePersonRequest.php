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
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'social_id' => 'required|integer|min:1000000000|max:9999999999|unique:people,social_id,' . $personId,
            'birth_date' => 'required|date',
            'mobile_number' => 'required|numeric|digits_between:11,15|unique:people,mobile_number,' . $personId,
            'email' => 'nullable|email|unique:people,email,' . $personId,
        ];
    }
}
