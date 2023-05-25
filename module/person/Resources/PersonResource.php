<?php


namespace PERSON\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'social_id' => $this->social_id,
            'birth_date' => $this->birth_date,
            'mobile_number' => $this->mobile_number,
            'mobile_number_description' => $this->mobile_number_description,
            'email' => $this->email,
            'email_description' => $this->email_description,
            'active' => $this->active,
        ];
    }
}
