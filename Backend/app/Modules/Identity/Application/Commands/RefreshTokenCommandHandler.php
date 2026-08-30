<?php

namespace App\Modules\Identity\Application\Commands;

use App\Core\Bus\CommandHandlerInterface;
use App\Core\Bus\CommandInterface;
use App\Core\Auth\JWT;
use App\Modules\Identity\Domain\UserRepositoryInterface;
use Exception;

class RefreshTokenCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function handle(CommandInterface $command): array
    {
        $payload = JWT::decode($command->refreshToken);

        if (!$payload) {
            throw new Exception("Invalid refresh token");
        }

        if (!isset($payload['type']) || $payload['type'] !== 'refresh') {
            throw new Exception("Invalid token type");
        }

        $userId = $payload['sub'] ?? null;

        if (!$userId) {
            throw new Exception("Invalid token payload");
        }

        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw new Exception("User not found");
        }

        // Generate new access token
        $newAccessToken = JWT::generateAccessToken($user->id, $user->email, $user->role);

        return [
            'access_token' => $newAccessToken,
            'token_type' => 'Bearer',
            'expires_in' => 3600, // 1 hour
            'expires_at' => date('Y-m-d H:i:s', time() + 3600)
        ];
    }
}
