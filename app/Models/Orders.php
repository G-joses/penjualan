<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    /**
     * fillable
     *
     * @var array
     */

    protected $fillable = [
        'user_id',
        'customer_name',
        'total_amount',
        'payment_status',
    ];
}
