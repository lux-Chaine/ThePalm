<?php

namespace App\Modules\Guests\Presentation;

use App\Core\Bus\CommandBus;
use App\Core\Bus\QueryBus;
use App\Core\Http\Request;
use App\Core\Http\ResponseFormatter;
use App\Core\Validation\Requests\CreateGuestRequest;
use App\Modules\Guests\Application\Commands\CreateGuestCommand;
use App\Modules\Guests\Application\Commands\UpdateGuestCommand;
use App\Modules\Guests\Application\Commands\DeleteGuestCommand;
use App\Modules\Guests\Application\Queries\GetGuestByIdQuery;
use App\Modules\Guests\Application\Queries\GetAllGuestsQuery;
use App\Modules\Guests\Application\Queries\SearchGuestsQuery;

class GuestController
{
    public function __construct(
        protected CommandBus $commandBus,
        protected QueryBus $queryBus
    ) {}

    public function index(Request $request): array
    {
        $query = new GetAllGuestsQuery(
            page: $request->get('page'),
            perPage: $request->get('per_page')
        );

        $guests = $this->queryBus->dispatch($query);

        return ResponseFormatter::collection($guests);
    }

    public function show(int $id): array
    {
        $query = new GetGuestByIdQuery($id);
        $guest = $this->queryBus->dispatch($query);

        if (!$guest) {
            return ResponseFormatter::notFound('Guest', $id);
        }

        return ResponseFormatter::item($guest->toArray());
    }

    public function store(Request $request): array
    {
        $formRequest = new CreateGuestRequest($request);
        
        if (!$formRequest->validate()) {
            return ResponseFormatter::validationError($formRequest->allErrors());
        }

        $validated = $formRequest->getRequest()->all();

        $command = new CreateGuestCommand(
            name: $validated['name'],
            phone: $validated['phone'],
            identityNumber: $validated['identity_number'] ?? null,
            identityType: $validated['identity_type'] ?? 'national_id',
            email: $validated['email'] ?? null,
            dateOfBirth: $validated['date_of_birth'] ?? null,
            address: $validated['address'] ?? null,
            city: $validated['city'] ?? null,
            country: $validated['country'] ?? 'Egypt',
            notes: $validated['notes'] ?? null
        );

        $guest = $this->commandBus->dispatch($command);

        return ResponseFormatter::created($guest->toArray());
    }

    public function update(Request $request, int $id): array
    {
        $errors = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'identity_number' => 'sometimes|string|max:50',
            'identity_type' => 'sometimes|in:national_id,passport,driving_license',
            'email' => 'sometimes|email|max:255',
            'date_of_birth' => 'sometimes|date',
            'address' => 'sometimes|string|max:500',
            'city' => 'sometimes|string|max:100',
            'country' => 'sometimes|string|max:100',
            'notes' => 'sometimes|string|max:1000'
        ]);

        if (!empty($errors)) {
            return ResponseFormatter::validationError($errors);
        }

        $command = new UpdateGuestCommand(
            guestId: $id,
            name: $request->get('name'),
            email: $request->get('email'),
            phone: $request->get('phone'),
            identityNumber: $request->get('identity_number'),
            identityType: $request->get('identity_type'),
            dateOfBirth: $request->get('date_of_birth'),
            address: $request->get('address'),
            city: $request->get('city'),
            country: $request->get('country'),
            notes: $request->get('notes')
        );

        $guest = $this->commandBus->dispatch($command);

        return ResponseFormatter::updated($guest->toArray());
    }

    public function destroy(int $id): array
    {
        $command = new DeleteGuestCommand($id);
        $this->commandBus->dispatch($command);

        return ResponseFormatter::deleted();
    }

    public function search(Request $request): array
    {
        $query = new SearchGuestsQuery(
            name: $request->get('name'),
            email: $request->get('email'),
            phone: $request->get('phone'),
            identityNumber: $request->get('identity_number'),
            city: $request->get('city'),
            country: $request->get('country')
        );

        $guests = $this->queryBus->dispatch($query);

        return ResponseFormatter::collection($guests);
    }
}
