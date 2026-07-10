<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,'sku'=>$this->sku,'name'=>$this->name,'slug'=>$this->slug,'condition'=>$this->condition,'warranty'=>$this->warranty,
            'oem_part_number'=>$this->oem_part_number,'alternate_part_numbers'=>$this->alternatePartNumbers->pluck('part_number'),
            'brand'=>$this->whenLoaded('brand'),'category'=>$this->whenLoaded('category'),'laptop_models'=>$this->whenLoaded('laptopModels'),
            'weight'=>$this->weight,'dimensions'=>$this->dimensions,'hsn_code'=>$this->hsn_code,'gst_rate'=>$this->gst_rate,'mrp'=>$this->mrp,
            'selling_price'=>$this->price,'cost_price'=>$this->cost_price,'minimum_order_quantity'=>$this->minimum_order_quantity,
            'stock_status'=>$this->stock_status,'status'=>$this->status,'featured'=>$this->is_featured,'trending'=>$this->is_trending,
            'meta_title'=>$this->meta_title,'meta_description'=>$this->meta_description,'meta_keywords'=>$this->meta_keywords,
            'created_by'=>$this->creator?->name,'updated_by'=>$this->updater?->name,'created_at'=>$this->created_at,'updated_at'=>$this->updated_at,'deleted_at'=>$this->deleted_at,
        ];
    }
}
