<?php

namespace App\Modules\Guests\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Bus\CommandInterface;
use App\Core\Database\UnitOfWorkInterface;
use App\Modules\Guests\Domain\Guest;
use App\Modules\Guests\Domain\GuestRepositoryInterface;
use Exception;

class UpdateGuestCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected GuestRepositoryInterface $guestRepository,
        protected UnitOfWorkInterface $unitOfWork
    ) {}

    public function handle(CommandInterface $command): Guest
    {
        return $this->unitOfWork->executeInTransaction(function () use ($command) {
            $guest = $this->guestRepository->findById($command->guestId);

            if (!$guest) {
                throw new Exception("Guest not found with ID: {$command->guestId}");
            }

            $data = array_filter([
                'name' => $command->name,
                'email' => $command->email,
                'phone' => $command->phone,
                'identity_number' => $command->identityNumber,
                'identity_type' => $command->identityType,
                'date_of_birth' => $command->dateOfBirth,
                'address' => $command->address,
                'city' => $command->city,
                'country' => $command->country,
                'notes' => $command->notes,
            ], fn($value) => $value !== null);

            return $this->guestRepository->update($guest, $data);
        });
    }
}
