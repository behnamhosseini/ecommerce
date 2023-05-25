<?php

namespace PERSON\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'active',
        'first_name',
        'last_name',
        'social_id',
        'birth_date',
        'mobile_number',
        'mobile_number_description',
        'email',
        'email_description',
    ];

    public function getDemonstrationNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
