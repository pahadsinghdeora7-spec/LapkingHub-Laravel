<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductLaptopModel extends Model
{
    use HasFactory, HasUuids;

    public const TYPE_ORIGINAL = 'original';
    public const TYPE_COMPATIBLE = 'compatible';
    public const TYPE_OEM = 'oem';
    public const TYPE_AFTERMARKET = 'aftermarket';
    public const TYPE_REPLACEMENT = 'replacement';

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    protected $table = 'product_laptop_models';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['priority' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
    }

    public static function compatibilityTypes(): array
    {
        return [self::TYPE_ORIGINAL, self::TYPE_COMPATIBLE, self::TYPE_OEM, self::TYPE_AFTERMARKET, self::TYPE_REPLACEMENT];
    }

    public static function statuses(): array
    {
        return [self::STATUS_ACTIVE, self::STATUS_INACTIVE];
    }

    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function laptopModel(): BelongsTo { return $this->belongsTo(LaptopModel::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function updater(): BelongsTo { return $this->belongsTo(User::class, 'updated_by'); }
}
