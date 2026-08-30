<?php

namespace App\Modules\Identity\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Database\UnitOfWorkInterface;
use App\Modules\Identity\Domain\User;
use App\Modules\Identity\Domain\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class CreateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected UnitOfWorkInterface $unitOfWork
    ) {}

    public function handle(CommandInterface $command): User
    {
        return $this->unitOfWork->executeInTransaction(function () use ($command) {
            $data = [
                'name' => $command->name,
                'email' => $command->email,
                'password' => Hash::make($command->password),
                'role' => $command->role,
                'user_type' => $command->userType,
            ];

            return $this->userRepository->create($data);
        });
    }
}
