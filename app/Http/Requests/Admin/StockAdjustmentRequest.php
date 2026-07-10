<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
class StockAdjustmentRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->can('adjust-stock') ?? false; }
    public function rules(): array { return ['movement_type'=>['required','in:increase,decrease,reserve,release,adjust'],'quantity'=>['required','integer','min:0'],'remarks'=>['nullable','string','max:1000']]; }
}
