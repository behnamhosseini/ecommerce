<?php

namespace INVOICE\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PERSON\Models\Person;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'total_sum',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

}
