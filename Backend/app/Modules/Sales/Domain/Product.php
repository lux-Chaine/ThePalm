<?php

namespace App\Modules\Sales\Domain;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'sku',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function decreaseStock(int $quantity): void
    {
        if ($quantity > $this->stock_quantity) {
            throw new \Exception("Insufficient stock for product: {$this->name}");
        }
        $this->stock_quantity -= $quantity;
    }

    public function increaseStock(int $quantity): void
    {
        $this->stock_quantity += $quantity;
    }
}
