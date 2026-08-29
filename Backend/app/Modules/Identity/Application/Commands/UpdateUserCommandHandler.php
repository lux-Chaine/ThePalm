<?php

namespace App\Modules\Identity\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Database\UnitOfWorkInterface;
use App\Modules\Identity\Domain\User;
use App\Modules\Identity\Domain\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Exception;

class UpdateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected UnitOfWorkInterface $unitOfWork
    ) {}

    public function handle(CommandInterface $command): User
    {
        return $this->unitOfWork->executeInTransaction(function () use ($command) {
            $user = $this->userRepository->findById($command->userId);

            if (!$user) {
                throw new Exception("User not found with ID: {$command->userId}");
            }

            $data = array_filter([
                'name' => $command->name,
                'email' => $command->email,
                'password' => $command->password ? Hash::make($command->password) : null,
                'role' => $command->role,
            ], fn($value) => $value !== null);

            return $this->userRepository->update($user, $data);
        });
    }
}
