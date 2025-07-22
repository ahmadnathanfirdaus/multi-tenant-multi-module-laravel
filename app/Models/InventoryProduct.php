<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class InventoryProduct extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'category',
        'price',
        'cost',
        'stock_quantity',
        'min_stock_level',
        'unit',
        'supplier',
        'is_active',
        'user_id',
        'tenant_id',
        'attributes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'is_active' => 'boolean',
        'attributes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'min_stock_level');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function isLowStock()
    {
        return $this->stock_quantity <= $this->min_stock_level;
    }

    public function getProfitMarginAttribute()
    {
        if ($this->cost && $this->price) {
            return (($this->price - $this->cost) / $this->price) * 100;
        }
        return 0;
    }
}
