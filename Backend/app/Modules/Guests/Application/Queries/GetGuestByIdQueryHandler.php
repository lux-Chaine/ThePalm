<?php

namespace App\Modules\Guests\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Core\Bus\QueryInterface;
use App\Modules\Guests\Domain\GuestRepositoryInterface;

class GetGuestByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected GuestRepositoryInterface $guestRepository
    ) {}

    public function handle(QueryInterface $query): ?array
    {
        $guest = $this->guestRepository->findById($query->guestId);

        return $guest ? $guest->toArray() : null;
    }
}
