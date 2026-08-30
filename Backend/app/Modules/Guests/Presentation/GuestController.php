<?php

namespace App\Modules\Guests\Presentation;

use App\Core\Bus\CommandBus;
use App\Core\Bus\QueryBus;
use App\Modules\Guests\Application\Commands\CreateGuestCommand;
use App\Modules\Guests\Application\Commands\UpdateGuestCommand;
use App\Modules\Guests\Application\Commands\DeleteGuestCommand;
use App\Modules\Guests\Application\Queries\GetGuestByIdQuery;
use App\Modules\Guests\Application\Queries\GetAllGuestsQuery;
use App\Modules\Guests\Application\Queries\SearchGuestsQuery;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GuestController
{
    public function __construct(
        protected CommandBus $commandBus,
        protected QueryBus $queryBus
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = new GetAllGuestsQuery(
            page: $request->get('page'),
            perPage: $request->get('per_page')
        );

        $guests = $this->queryBus->dispatch($query);

        return response()->json([
            'success' => true,
            'data' => $guests
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $query = new GetGuestByIdQuery($id);
        $guest = $this->queryBus->dispatch($query);

        return response()->json([
            'success' => true,
            'data' => $guest
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'identity_number' => 'required|string|max:50',
            'identity_type' => 'sometimes|in:national_id,passport,driving_license',
            'email' => 'sometimes|email|max:255',
            'date_of_birth' => 'sometimes|date',
            'address' => 'sometimes|string|max:500',
            'city' => 'sometimes|string|max:100',
            'country' => 'sometimes|string|max:100',
            'notes' => 'sometimes|string|max:1000'
        ]);

        $command = new CreateGuestCommand(
            name: $validated['name'],
            phone: $validated['phone'],
            identityNumber: $validated['identity_number'],
            identityType: $validated['identity_type'] ?? 'national_id',
            email: $validated['email'] ?? null,
            dateOfBirth: $validated['date_of_birth'] ?? null,
            address: $validated['address'] ?? null,
            city: $validated['city'] ?? null,
            country: $validated['country'] ?? 'Egypt',
            notes: $validated['notes'] ?? null
        );

        $guest = $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'data' => $guest->toArray()
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
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

        $command = new UpdateGuestCommand(
            guestId: $id,
            name: $validated['name'] ?? null,
            email: $validated['email'] ?? null,
            phone: $validated['phone'] ?? null,
            identityNumber: $validated['identity_number'] ?? null,
            identityType: $validated['identity_type'] ?? null,
            dateOfBirth: $validated['date_of_birth'] ?? null,
            address: $validated['address'] ?? null,
            city: $validated['city'] ?? null,
            country: $validated['country'] ?? null,
            notes: $validated['notes'] ?? null
        );

        $guest = $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'data' => $guest->toArray()
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $command = new DeleteGuestCommand($id);
        $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'message' => 'Guest deleted successfully'
        ]);
    }

    public function search(Request $request): JsonResponse
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

        return response()->json([
            'success' => true,
            'data' => $guests
        ]);
    }
}
