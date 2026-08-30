<?php

namespace App\Modules\Identity\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Core\Bus\QueryInterface;
use App\Modules\Identity\Domain\UserRepositoryInterface;

class GetUserPermissionsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function handle(QueryInterface $query): array
    {
        $user = $this->userRepository->findById($query->userId);
        
        if (!$user) {
            throw new \Exception("User not found");
        }

        return [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'user_type' => $user->user_type,
            'permissions' => $user->getPermissions()
        ];
    }
}
