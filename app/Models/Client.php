<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'doc_type',
        'doc_number',
        'country',
        'state',
        'city',
        'address',
        'cellphone',
        'email',
        'company_id',
    ];
}
