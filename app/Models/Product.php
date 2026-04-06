<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $table = '23810310115_products';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock_quantity',
        'image_path',
        'status',
        'category_id',
        'discount_percent',
    ];

    // quan hệ: product thuộc về category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}