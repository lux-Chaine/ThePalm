<?php

namespace App\Modules\Reservations\Presentation;

use App\Core\Bus\CommandBus;
use App\Core\Bus\QueryBus;
use App\Core\Http\Request;
use App\Core\Http\ResponseFormatter;
use App\Core\Validation\Requests\CreateReservationRequest;
use App\Modules\Reservations\Application\Commands\CreateReservationCommand;
use App\Modules\Reservations\Application\Commands\UpdateReservationStatusCommand;
use App\Modules\Reservations\Application\Queries\GetReservationByIdQuery;
use App\Modules\Reservations\Application\Queries\GetAllReservationsQuery;

class ReservationController
{
    public function __construct(
        protected CommandBus $commandBus,
        protected QueryBus $queryBus
    ) {}

    public function index(Request $request): array
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

        return ResponseFormatter::collection($reservations);
    }

    public function show(int $id): array
    {
        $query = new GetReservationByIdQuery($id);
        $reservation = $this->queryBus->dispatch($query);

        if (!$reservation) {
            return ResponseFormatter::notFound('Reservation', $id);
        }

        return ResponseFormatter::item($reservation->toArray());
    }

    public function store(Request $request): array
    {
        $formRequest = new CreateReservationRequest($request);
        
        if (!$formRequest->validate()) {
            return ResponseFormatter::validationError($formRequest->allErrors());
        }

        $validated = $formRequest->getRequest()->all();

        $command = new CreateReservationCommand(
            guestId: $validated['guest_id'],
            roomId: $validated['room_id'],
            userId: $validated['user_id'],
            checkInDate: $validated['check_in_date'],
            checkOutDate: $validated['check_out_date'],
            numberOfGuests: $validated['number_of_guests'] ?? 1,
            specialRequests: $validated['special_requests'] ?? null
        );

        $reservation = $this->commandBus->dispatch($command);

        return ResponseFormatter::created($reservation->toArray());
    }

    public function updateStatus(Request $request, int $id): array
    {
        $errors = $request->validate([
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
            'cancellation_reason' => 'sometimes|string'
        ]);

        if (!empty($errors)) {
            return ResponseFormatter::validationError($errors);
        }

        $command = new UpdateReservationStatusCommand(
            reservationId: $id,
            status: $request->get('status'),
            cancellationReason: $request->get('cancellation_reason')
        );

        $reservation = $this->commandBus->dispatch($command);

        return ResponseFormatter::updated($reservation->toArray());
    }
}
