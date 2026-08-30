<?php

namespace App\Modules\Identity\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Bus\CommandInterface;
use App\Modules\Identity\Domain\UserRepositoryInterface;

class ResetPasswordCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function handle(CommandInterface $command): array
    {
        $user = $this->userRepository->findById($command->userId);
        
        if (!$user) {
            throw new \Exception("User not found");
        }

        $hashedPassword = password_hash($command->newPassword, PASSWORD_DEFAULT);
        
        $this->userRepository->update($user, ['password' => $hashedPassword]);

        return [
            'message' => 'Password reset successfully',
            'user' => $user->fresh()->toArray()
        ];
    }
}
