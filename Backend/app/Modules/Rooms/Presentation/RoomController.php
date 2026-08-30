<?php

namespace App\Modules\Rooms\Presentation;

use App\Core\Bus\CommandBus;
use App\Core\Bus\QueryBus;
use App\Core\Http\Request;
use App\Core\Http\ResponseFormatter;
use App\Modules\Rooms\Application\Commands\CreateRoomCommand;
use App\Modules\Rooms\Application\Commands\UpdateRoomCommand;
use App\Modules\Rooms\Application\Queries\GetRoomByIdQuery;
use App\Modules\Rooms\Application\Queries\GetAllRoomsQuery;

class RoomController
{
    public function __construct(
        protected CommandBus $commandBus,
        protected QueryBus $queryBus
    ) {}

    public function index(Request $request): array
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

        return ResponseFormatter::collection($rooms);
    }

    public function show(int $id): array
    {
        $query = new GetRoomByIdQuery($id);
        $room = $this->queryBus->dispatch($query);

        if (!$room) {
            return ResponseFormatter::notFound('Room', $id);
        }

        return ResponseFormatter::item($room->toArray());
    }

    public function store(Request $request): array
    {
        $errors = $request->validate([
            'room_number' => 'required|string',
            'type' => 'required|in:single,double,suite,deluxe,presidential',
            'price_per_night' => 'required|numeric|min:0',
            'floor' => 'sometimes|integer|min:1',
            'capacity' => 'sometimes|integer|min:1',
            'description' => 'sometimes|string',
            'amenities' => 'sometimes|array'
        ]);

        if (!empty($errors)) {
            return ResponseFormatter::validationError($errors);
        }

        $command = new CreateRoomCommand(
            roomNumber: $request->get('room_number'),
            type: $request->get('type'),
            pricePerNight: $request->get('price_per_night'),
            floor: $request->get('floor') ?? 1,
            capacity: $request->get('capacity') ?? 2,
            description: $request->get('description'),
            amenities: $request->get('amenities')
        );

        $room = $this->commandBus->dispatch($command);

        return ResponseFormatter::created($room->toArray());
    }

    public function update(Request $request, int $id): array
    {
        $errors = $request->validate([
            'room_number' => 'sometimes|string',
            'type' => 'sometimes|in:single,double,suite,deluxe,presidential',
            'price_per_night' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:available,booked,cleaning,maintenance',
            'floor' => 'sometimes|integer|min:1',
            'capacity' => 'sometimes|integer|min:1',
            'description' => 'sometimes|string',
            'amenities' => 'sometimes|array'
        ]);

        if (!empty($errors)) {
            return ResponseFormatter::validationError($errors);
        }

        $command = new UpdateRoomCommand(
            roomId: $id,
            roomNumber: $request->get('room_number'),
            type: $request->get('type'),
            pricePerNight: $request->get('price_per_night'),
            status: $request->get('status'),
            floor: $request->get('floor'),
            capacity: $request->get('capacity'),
            description: $request->get('description'),
            amenities: $request->get('amenities')
        );

        $room = $this->commandBus->dispatch($command);

        return ResponseFormatter::updated($room->toArray());
    }
}
