<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'description', 'price', 'category_id', 'image_url'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

}
