<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public const CONDITION_NEW = 'new';
    public const CONDITION_REFURBISHED = 'refurbished';
    public const CONDITION_USED = 'used';
    public const STOCK_IN_STOCK = 'in_stock';
    public const STOCK_OUT_OF_STOCK = 'out_of_stock';
    public const STOCK_PREORDER = 'preorder';
    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_ARCHIVED = 'archived';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'dimensions' => 'array',
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'mrp' => 'decimal:2',
            'gst_rate' => 'decimal:2',
            'weight' => 'decimal:3',
            'minimum_order_quantity' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_trending' => 'boolean',
            'published_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public static function conditions(): array { return [self::CONDITION_NEW, self::CONDITION_REFURBISHED, self::CONDITION_USED]; }
    public static function stockStatuses(): array { return [self::STOCK_IN_STOCK, self::STOCK_OUT_OF_STOCK, self::STOCK_PREORDER]; }
    public static function statuses(): array { return [self::STATUS_DRAFT, self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_ARCHIVED]; }

    public function brand(): BelongsTo { return $this->belongsTo(Brand::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function subCategory(): BelongsTo { return $this->belongsTo(SubCategory::class); }
    public function alternatePartNumbers(): HasMany { return $this->hasMany(ProductAlternatePartNumber::class); }
    public function images(): HasMany { return $this->hasMany(ProductImage::class); }
    public function attributes(): HasMany { return $this->hasMany(ProductAttribute::class); }
    public function compatibleModels(): HasMany { return $this->hasMany(CompatibleModel::class); }
    public function laptopModels(): BelongsToMany { return $this->belongsToMany(LaptopModel::class, 'product_laptop_models')->using(ProductLaptopModelPivot::class)->withPivot(['id', 'compatibility_type', 'oem_part_number', 'notes', 'priority', 'status', 'created_by', 'updated_by'])->withTimestamps(); }
    public function inventory(): HasMany { return $this->hasMany(Inventory::class); }
    public function orderItems(): HasMany { return $this->hasMany(OrderItem::class); }
    public function wishlists(): HasMany { return $this->hasMany(Wishlist::class); }
    public function carts(): HasMany { return $this->hasMany(Cart::class); }
    public function reviews(): HasMany { return $this->hasMany(Review::class); }
    public function seo(): MorphOne { return $this->morphOne(Seo::class, 'seoable'); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function updater(): BelongsTo { return $this->belongsTo(User::class, 'updated_by'); }
}
