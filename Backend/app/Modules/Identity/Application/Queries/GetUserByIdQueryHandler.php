<?php

namespace App\Modules\Identity\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Modules\Identity\Domain\User;
use App\Modules\Identity\Domain\UserRepositoryInterface;
use Exception;

class GetUserByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function handle(QueryInterface $query): User
    {
        $user = $this->userRepository->findById($query->userId);

        if (!$user) {
            throw new Exception("User not found with ID: {$query->userId}");
        }

        return $user;
    }
}
