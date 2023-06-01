<?php

declare(strict_types = 1);

namespace App\Models;

use App\Enums\Product\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{MorphMany, MorphToMany};
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'status',
    ];

    protected $casts = [
        'status' => StatusEnum::class,
    ];

    /**
     * @return MorphMany<Image>
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
     * @return MorphToMany<Category>
     */
    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    public static function booted()
    {
        static::creating(fn (Product $category) => $category->slug = Str::slug($category->name));
        static::updating(fn (Product $category) => $category->slug = Str::slug($category->name));
    }
}
