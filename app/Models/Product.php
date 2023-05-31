<?php

declare(strict_types = 1);

namespace App\Models;

use App\Enums\Product\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
