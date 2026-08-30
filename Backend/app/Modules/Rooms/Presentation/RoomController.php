<?php

namespace App\Modules\Rooms\Presentation;

use App\Core\Bus\CommandBus;
use App\Core\Bus\QueryBus;
use App\Modules\Rooms\Application\Commands\CreateRoomCommand;
use App\Modules\Rooms\Application\Commands\UpdateRoomCommand;
use App\Modules\Rooms\Application\Queries\GetRoomByIdQuery;
use App\Modules\Rooms\Application\Queries\GetAllRoomsQuery;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RoomController
{
    public function __construct(
        protected CommandBus $commandBus,
        protected QueryBus $queryBus
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = new GetAllRoomsQuery(
            type: $request->get('type'),
            status: $request->get('status'),
            checkIn: $request->get('check_in'),
            checkOut: $request->get('check_out'),
            page: $request->get('page'),
            perPage: $request->get('per_page')
        );

        $rooms = $this->queryBus->dispatch($query);

        return response()->json([
            'success' => true,
            'data' => $rooms
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $query = new GetRoomByIdQuery($id);
        $room = $this->queryBus->dispatch($query);

        if (!$room) {
            return response()->json([
                'success' => false,
                'error' => 'Room not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $room
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number',
            'type' => 'required|in:single,double,suite,deluxe,presidential',
            'price_per_night' => 'required|numeric|min:0',
            'floor' => 'sometimes|integer|min:1',
            'capacity' => 'sometimes|integer|min:1',
            'description' => 'sometimes|string',
            'amenities' => 'sometimes|array'
        ]);

        $command = new CreateRoomCommand(
            roomNumber: $validated['room_number'],
            type: $validated['type'],
            pricePerNight: $validated['price_per_night'],
            floor: $validated['floor'] ?? 1,
            capacity: $validated['capacity'] ?? 2,
            description: $validated['description'] ?? null,
            amenities: $validated['amenities'] ?? null
        );

        $room = $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'data' => $room->toArray()
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'room_number' => 'sometimes|string|unique:rooms,room_number,' . $id,
            'type' => 'sometimes|in:single,double,suite,deluxe,presidential',
            'price_per_night' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:available,booked,cleaning,maintenance',
            'floor' => 'sometimes|integer|min:1',
            'capacity' => 'sometimes|integer|min:1',
            'description' => 'sometimes|string',
            'amenities' => 'sometimes|array'
        ]);

        $command = new UpdateRoomCommand(
            roomId: $id,
            roomNumber: $validated['room_number'] ?? null,
            type: $validated['type'] ?? null,
            pricePerNight: $validated['price_per_night'] ?? null,
            status: $validated['status'] ?? null,
            floor: $validated['floor'] ?? null,
            capacity: $validated['capacity'] ?? null,
            description: $validated['description'] ?? null,
            amenities: $validated['amenities'] ?? null
        );

        $room = $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'data' => $room->toArray()
        ]);
    }
}
