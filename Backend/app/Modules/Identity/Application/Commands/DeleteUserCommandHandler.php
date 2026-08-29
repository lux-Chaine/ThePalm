<?php

namespace App\Modules\Identity\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Database\UnitOfWorkInterface;
use App\Modules\Identity\Domain\UserRepositoryInterface;
use Exception;

class DeleteUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected UnitOfWorkInterface $unitOfWork
    ) {}

    public function handle(CommandInterface $command): bool
    {
        return $this->unitOfWork->executeInTransaction(function () use ($command) {
            $user = $this->userRepository->findById($command->userId);

            if (!$user) {
                throw new Exception("User not found with ID: {$command->userId}");
            }

            return $this->userRepository->delete($user);
        });
    }
}
