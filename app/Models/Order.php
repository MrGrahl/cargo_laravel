<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'created_by',
        'delivered_at',
        'driver_id',
        // 'client_id',
        'order_cost',
        'delivery_cost',
        'description',
        'status',
        'client_name',
        'client_phone',
        'client_address',
        'client_latitude',
        'client_longitude',
    ];


    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function driver()
    {
        return $this->belongsTo(Driver::class,'driver_id','id');
    }
    // public function client()
    // {
    //     return $this->belongsTo(Client::class);
    // }
}
