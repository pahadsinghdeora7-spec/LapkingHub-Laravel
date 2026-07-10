<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public const STATUS_IN_STOCK = 'in_stock';
    public const STATUS_LOW_STOCK = 'low_stock';
    public const STATUS_OUT_OF_STOCK = 'out_of_stock';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['available_qty'=>'integer','reserved_qty'=>'integer','damaged_qty'=>'integer','minimum_stock'=>'integer','reorder_level'=>'integer','maximum_stock'=>'integer','created_at'=>'datetime','updated_at'=>'datetime','deleted_at'=>'datetime'];
    }

    public static function statuses(): array { return [self::STATUS_IN_STOCK, self::STATUS_LOW_STOCK, self::STATUS_OUT_OF_STOCK]; }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function stockMovements(): HasMany { return $this->hasMany(StockMovement::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function updater(): BelongsTo { return $this->belongsTo(User::class, 'updated_by'); }
    public function getStockStatusAttribute(): string { return $this->status; }
}
