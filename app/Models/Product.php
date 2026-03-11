<?php
// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'description',
        'sku',
        'ean_13',
        'ncm',
        'category_id',
        'cost_price',
        'operational_cost',
        'price',
        'iof_rate',
        'stock_quantity',
        'min_stock',
        'image_path',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'operational_cost' => 'decimal:2',
        'price' => 'decimal:2',
        'iof_rate' => 'decimal:2',
        'stock_quantity' => 'integer',
        'min_stock' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function inputs(): HasMany
    {
        return $this->hasMany(Input::class);
    }

    public function outputs(): HasMany
    {
        return $this->hasMany(Output::class);
    }
}