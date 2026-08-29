<?php

namespace App\Modules\Sales\Presentation;

use App\Core\Bus\CommandBus;
use App\Core\Bus\QueryBus;
use App\Modules\Sales\Application\Commands\CreateProductCommand;
use App\Modules\Sales\Application\Commands\UpdateProductCommand;
use App\Modules\Sales\Application\Queries\GetProductByIdQuery;
use App\Modules\Sales\Application\Queries\GetAllProductsQuery;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController
{
    public function __construct(
        protected CommandBus $commandBus,
        protected QueryBus $queryBus
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = new GetAllProductsQuery(
            activeOnly: $request->boolean('active_only')
        );

        $products = $this->queryBus->dispatch($query);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $query = new GetProductByIdQuery($id);
        $product = $this->queryBus->dispatch($query);

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku',
            'is_active' => 'sometimes|boolean'
        ]);

        $command = new CreateProductCommand(
            name: $validated['name'],
            description: $validated['description'],
            price: (float) $validated['price'],
            stockQuantity: (int) $validated['stock_quantity'],
            sku: $validated['sku'],
            isActive: $validated['is_active'] ?? true
        );

        $product = $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'data' => $product
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
            'sku' => 'sometimes|string|unique:products,sku,' . $id,
            'is_active' => 'sometimes|boolean'
        ]);

        $command = new UpdateProductCommand(
            productId: $id,
            name: $validated['name'] ?? null,
            description: $validated['description'] ?? null,
            price: isset($validated['price']) ? (float) $validated['price'] : null,
            stockQuantity: isset($validated['stock_quantity']) ? (int) $validated['stock_quantity'] : null,
            sku: $validated['sku'] ?? null,
            isActive: $validated['is_active'] ?? null
        );

        $product = $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }
}
