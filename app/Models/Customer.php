<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_ON_HOLD = 'on_hold';
    public const STATUS_BLOCKED = 'blocked';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'credit_limit' => 'decimal:2',
            'available_credit' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public static function statuses(): array
    {
        return [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_ON_HOLD, self::STATUS_BLOCKED];
    }

    public static function businessTypes(): array
    {
        return ['retailer', 'distributor', 'repair_center', 'corporate', 'other'];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function addresses(): HasMany { return $this->hasMany(CustomerAddress::class); }
    public function orders(): HasMany { return $this->hasMany(Order::class); }
    public function couponUsage(): HasMany { return $this->hasMany(CouponUsage::class); }
    public function wishlists(): HasMany { return $this->hasMany(Wishlist::class); }
    public function carts(): HasMany { return $this->hasMany(Cart::class); }
    public function reviews(): HasMany { return $this->hasMany(Review::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function updater(): BelongsTo { return $this->belongsTo(User::class, 'updated_by'); }
}
