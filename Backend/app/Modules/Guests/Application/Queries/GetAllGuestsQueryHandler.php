<?php

namespace App\Modules\Guests\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Core\Bus\QueryInterface;
use App\Modules\Guests\Domain\GuestRepositoryInterface;

class GetAllGuestsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected GuestRepositoryInterface $guestRepository
    ) {}

    public function handle(QueryInterface $query): array
    {
        $guests = $this->guestRepository->all();

        return $guests->map(fn($guest) => $guest->toArray())->toArray();
    }
}
