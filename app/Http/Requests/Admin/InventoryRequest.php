<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
class InventoryRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->can('manage-inventory') ?? false; }
    public function rules(): array { return ['product_id'=>['required','uuid','exists:products,id'],'warehouse'=>['required','string','max:100'],'available_qty'=>['required','integer','min:0'],'reserved_qty'=>['nullable','integer','min:0'],'damaged_qty'=>['nullable','integer','min:0'],'minimum_stock'=>['nullable','integer','min:0'],'reorder_level'=>['nullable','integer','min:0'],'maximum_stock'=>['nullable','integer','min:0']]; }
}
