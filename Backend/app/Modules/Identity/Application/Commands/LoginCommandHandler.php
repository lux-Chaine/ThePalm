<?php

namespace App\Modules\Identity\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Bus\CommandInterface;
use App\Core\Auth\JWT;
use App\Modules\Identity\Domain\UserRepositoryInterface;
use App\Modules\Identity\Infrastructure\EloquentUserRepository;

class LoginCommandHandler implements CommandHandlerInterface
{
    public function __construct()
    {
        $this->userRepository = new EloquentUserRepository();
    }

    public function handle(CommandInterface $command): array
    {
        $user = $this->userRepository->findByEmail($command->email);
        
        if (!$user) {
            throw new \Exception("Invalid credentials");
        }

        if (!password_verify($command->password, $user->password)) {
            throw new \Exception("Invalid credentials");
        }

        // Generate JWT tokens
        $accessToken = JWT::generateAccessToken($user->id, $user->email, $user->role);
        $refreshToken = JWT::generateRefreshToken($user->id);

        return [
            'user' => $user->toArray(),
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'expires_in' => 3600, // 1 hour
            'expires_at' => date('Y-m-d H:i:s', time() + 3600)
        ];
    }
}
