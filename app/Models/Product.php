<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'description',
        'price', 'stock', 'image_url',
    ];

    protected $appends = ['image_url', 'images_urls'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        $first = $this->relationLoaded('images')
            ? $this->images->first()
            : $this->images()->first();

        return $first ? Storage::url($first->path) : null;
    }

    public function getImagesUrlsAttribute(): array
    {
        $images = $this->relationLoaded('images')
            ? $this->images
            : $this->images()->get();

        return $images->map(fn($img) => Storage::url($img->path))->toArray();
    }
}
