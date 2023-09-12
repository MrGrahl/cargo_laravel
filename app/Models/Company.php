<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'industry',
        'registration_number',
        'country',
        'address',
        'city',
        'state',
        'postal_code',
    ];
}
