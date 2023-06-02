<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * @return MorphToMany<Product>
     */
    public function products(): MorphToMany
    {
        return $this->morphToMany(Product::class, 'categorizable');
    }

    public static function booted()
    {
        static::creating(fn (Category $category) => $category->slug = Str::slug($category->name));
        static::updating(fn (Category $category) => $category->slug = Str::slug($category->name));
    }
}
