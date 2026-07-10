<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CompatibilityService;
use Illuminate\View\View;

class ProductCompatibilityController extends Controller
{
    public function __construct(private readonly CompatibilityService $compatibilities) {}

    public function show(Product $product): View
    {
        $product->load('brand:id,name');
        return view('products.compatible-laptop-models', ['product' => $product, 'groups' => $this->compatibilities->groupedForProduct($product)]);
    }
}
