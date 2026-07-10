<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['quantity'=>'integer','previous_stock'=>'integer','current_stock'=>'integer','created_at'=>'datetime','updated_at'=>'datetime'];
    }

    public function inventory(): BelongsTo { return $this->belongsTo(Inventory::class); }
    public function reference(): MorphTo { return $this->morphTo(); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
}
