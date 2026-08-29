<?php

namespace App\Modules\Sales\Infrastructure;

use App\Modules\Sales\Domain\Product;
use App\Modules\Sales\Domain\ProductRepositoryInterface;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }

    public function findBySku(string $sku): ?Product
    {
        return Product::where('sku', $sku)->first();
    }

    public function findAll(): array
    {
        return Product::all()->toArray();
    }

    public function findActive(): array
    {
        return Product::where('is_active', true)->get()->toArray();
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->fresh();
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }
}
