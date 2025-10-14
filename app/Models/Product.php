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
    ];

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
