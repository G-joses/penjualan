<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['invoice', 'customer_name', 'total', 'payment', 'change_amount', 'subtotal', 'tax', 'discount'];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function getItemIds()
    {
        return $this->items->pluck('id');
    }
}
