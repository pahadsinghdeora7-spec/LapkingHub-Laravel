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

class Customer extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function addresses(): HasMany { return $this->hasMany(CustomerAddress::class); }
    public function orders(): HasMany { return $this->hasMany(Order::class); }
    public function couponUsage(): HasMany { return $this->hasMany(CouponUsage::class); }
    public function wishlists(): HasMany { return $this->hasMany(Wishlist::class); }
    public function carts(): HasMany { return $this->hasMany(Cart::class); }
    public function reviews(): HasMany { return $this->hasMany(Review::class); }

}
