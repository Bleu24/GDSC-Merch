<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'image', 'category', 'stock'];

    public static function categories()
    {
        return ['Lanyard', 'Shirt', 'Hoodie'];
    }

    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->price, 2);
    }

    public function inStock(): bool
    {
        return $this->stock > 0;
    }

    
}
