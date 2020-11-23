<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Order extends Model
{
    protected $fillable = [
        'payment_ref', 'amount', 'status', 'driver_id', 'date_delivered', 'items', 'user_id', 'address'
    ];

    public function users() {
        return $this->belongsTo(User::class);
    }
}
