<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = '23810310115_categories';

    // cho phép insert dữ liệu
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_visible',
    ];

    // quan hệ: 1 category có nhiều product
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}