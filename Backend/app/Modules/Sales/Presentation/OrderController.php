<?php

namespace App\Modules\Sales\Presentation;

use App\Core\Bus\CommandBus;
use App\Modules\Sales\Application\Commands\CreateOrderCommand;
use App\Modules\Sales\Application\Commands\UpdateOrderStatusCommand;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderController
{
    public function __construct(
        protected CommandBus $commandBus
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'order_number' => 'required|string|unique:orders,order_number',
            'total_amount' => 'required|numeric|min:0',
            'shipping_address' => 'required|string',
            'notes' => 'sometimes|nullable|string'
        ]);

        $command = new CreateOrderCommand(
            userId: (int) $validated['user_id'],
            orderNumber: $validated['order_number'],
            totalAmount: (float) $validated['total_amount'],
            shippingAddress: $validated['shipping_address'],
            notes: $validated['notes'] ?? null
        );

        $order = $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'data' => $order
        ], 201);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,cancelled'
        ]);

        $command = new UpdateOrderStatusCommand(
            orderId: $id,
            status: $validated['status']
        );

        $order = $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }
}
