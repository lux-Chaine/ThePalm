<?php

namespace App\Modules\Guests\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Core\Bus\QueryInterface;
use App\Modules\Guests\Domain\GuestRepositoryInterface;

class SearchGuestsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected GuestRepositoryInterface $guestRepository
    ) {}

    public function handle(QueryInterface $query): array
    {
        $filters = array_filter([
            'name' => $query->name,
            'email' => $query->email,
            'phone' => $query->phone,
            'identity_number' => $query->identityNumber,
            'city' => $query->city,
            'country' => $query->country,
        ], fn($value) => $value !== null);

        $guests = $this->guestRepository->search($filters);

        return $guests->map(fn($guest) => $guest->toArray())->toArray();
    }
}
