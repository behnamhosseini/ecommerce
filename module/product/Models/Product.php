<?php

namespace PRODUCT\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'active',
        'selling_price',
        'tax',
        'discount_percentage',
        'inventory',
    ];

}
