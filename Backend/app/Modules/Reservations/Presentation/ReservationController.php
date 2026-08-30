<?php

namespace App\Modules\Reservations\Presentation;

use App\Core\Bus\CommandBus;
use App\Core\Bus\QueryBus;
use App\Modules\Reservations\Application\Commands\CreateReservationCommand;
use App\Modules\Reservations\Application\Commands\UpdateReservationStatusCommand;
use App\Modules\Reservations\Application\Queries\GetReservationByIdQuery;
use App\Modules\Reservations\Application\Queries\GetAllReservationsQuery;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReservationController
{
    public function __construct(
        protected CommandBus $commandBus,
        protected QueryBus $queryBus
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = new GetAllReservationsQuery(
            guestId: $request->get('guest_id'),
            roomId: $request->get('room_id'),
            userId: $request->get('user_id'),
            status: $request->get('status'),
            startDate: $request->get('start_date'),
            endDate: $request->get('end_date'),
            page: $request->get('page'),
            perPage: $request->get('per_page')
        );

        $reservations = $this->queryBus->dispatch($query);

        return response()->json([
            'success' => true,
            'data' => $reservations
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $query = new GetReservationByIdQuery($id);
        $reservation = $this->queryBus->dispatch($query);

        if (!$reservation) {
            return response()->json([
                'success' => false,
                'error' => 'Reservation not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $reservation
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'guest_id' => 'required|integer|exists:guests,id',
            'room_id' => 'required|integer|exists:rooms,id',
            'user_id' => 'required|integer|exists:users,id',
            'check_in' => 'required|date|after:today',
            'check_out' => 'required|date|after:check_in',
            'number_of_guests' => 'sometimes|integer|min:1',
            'special_requests' => 'sometimes|string'
        ]);

        $command = new CreateReservationCommand(
            guestId: $validated['guest_id'],
            roomId: $validated['room_id'],
            userId: $validated['user_id'],
            checkInDate: $validated['check_in'],
            checkOutDate: $validated['check_out'],
            numberOfGuests: $validated['number_of_guests'] ?? 1,
            specialRequests: $validated['special_requests'] ?? null
        );

        $reservation = $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'data' => $reservation->toArray()
        ], 201);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
            'cancellation_reason' => 'sometimes|string'
        ]);

        $command = new UpdateReservationStatusCommand(
            reservationId: $id,
            status: $validated['status'],
            cancellationReason: $validated['cancellation_reason'] ?? null
        );

        $reservation = $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'data' => $reservation->toArray()
        ]);
    }
}
