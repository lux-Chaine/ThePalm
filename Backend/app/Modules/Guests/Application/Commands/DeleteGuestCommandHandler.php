<?php

namespace App\Modules\Guests\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Bus\CommandInterface;
use App\Core\Database\UnitOfWorkInterface;
use App\Modules\Guests\Domain\Guest;
use App\Modules\Guests\Domain\GuestRepositoryInterface;
use Exception;

class DeleteGuestCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected GuestRepositoryInterface $guestRepository,
        protected UnitOfWorkInterface $unitOfWork
    ) {}

    public function handle(CommandInterface $command): bool
    {
        return $this->unitOfWork->executeInTransaction(function () use ($command) {
            $guest = $this->guestRepository->findById($command->guestId);

            if (!$guest) {
                throw new Exception("Guest not found with ID: {$command->guestId}");
            }

            return $this->guestRepository->delete($guest);
        });
    }
}
