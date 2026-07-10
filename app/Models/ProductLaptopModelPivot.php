<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductLaptopModelPivot extends Pivot
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'product_laptop_models';
    protected $guarded = ['id'];
}
