<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currencyrates extends Model
{
    use HasFactory;
    protected $fillable = [
        'country',
        'rate',
    ];

}
