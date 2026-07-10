<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array { return ['dimensions'=>'array','is_active'=>'boolean','is_featured'=>'boolean','published_at'=>'datetime']; }
    public function brand(): BelongsTo { return $this->belongsTo(Brand::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function subCategory(): BelongsTo { return $this->belongsTo(SubCategory::class); }
    public function images(): HasMany { return $this->hasMany(ProductImage::class); }
    public function attributes(): HasMany { return $this->hasMany(ProductAttribute::class); }
    public function compatibleModels(): HasMany { return $this->hasMany(CompatibleModel::class); }
    public function inventory(): HasMany { return $this->hasMany(Inventory::class); }
    public function orderItems(): HasMany { return $this->hasMany(OrderItem::class); }
    public function wishlists(): HasMany { return $this->hasMany(Wishlist::class); }
    public function carts(): HasMany { return $this->hasMany(Cart::class); }
    public function reviews(): HasMany { return $this->hasMany(Review::class); }
    public function seo(): MorphOne { return $this->morphOne(Seo::class, 'seoable'); }

}
