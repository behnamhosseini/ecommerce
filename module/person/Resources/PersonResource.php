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
            'email' => $this->email,
        ];
    }
}
