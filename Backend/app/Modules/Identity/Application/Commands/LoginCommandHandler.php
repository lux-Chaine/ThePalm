<?php

namespace App\Modules\Identity\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Bus\CommandInterface;
use App\Modules\Identity\Domain\UserRepositoryInterface;

class LoginCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function handle(CommandInterface $command): array
    {
        $user = $this->userRepository->findByEmail($command->email);
        
        if (!$user) {
            throw new \Exception("Invalid credentials");
        }

        if (!password_verify($command->password, $user->password)) {
            throw new \Exception("Invalid credentials");
        }

        // Generate simple token (in production, use JWT)
        $token = base64_encode($user->id . ':' . $user->email . ':' . time());

        return [
            'user' => $user->toArray(),
            'token' => $token,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+24 hours'))
        ];
    }
}
