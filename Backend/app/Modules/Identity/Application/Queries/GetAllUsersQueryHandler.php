<?php

namespace App\Modules\Identity\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Modules\Identity\Domain\UserRepositoryInterface;

class GetAllUsersQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function handle(QueryInterface $query): array
    {
        return $this->userRepository->findAll();
    }
}
