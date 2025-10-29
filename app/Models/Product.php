<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    /**
     * fillable
     *
     * @var array
     */

    protected $fillable = [
        'image',
        'name',
        'category_id',
        'description',
        'price',
        'stock',
        'discount',
        'discount_amount',
        'has_discount',
        'final_price'
    ];

    protected $appends = ['price_after_discount'];

    public function getPriceAfterDiscountAttribute()
    {
        if (!$this->has_discount) {
            return $this->price;
        }
        if (!$this->discount_amount > 0) {
            return max(0, $this->price - $this->discount_amount);
        } else {
            return $this->price * (1 - ($this->discount / 100));
        }
    }

    public function getDiscountDisplayAttribute()
    {
        if (!$this->has_amount > 0) {
            return 'Rp ' . number_format($this->discount_amount, 0, ',', '.');
        } else {
            return $this->discount . '%';
        }
    }

    /**
     * relasi
     *
     * @var array
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
