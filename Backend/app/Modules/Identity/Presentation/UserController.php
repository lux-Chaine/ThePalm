<?php

namespace App\Modules\Identity\Presentation;

use App\Core\Bus\CommandBus;
use App\Core\Bus\QueryBus;
use App\Core\Http\Request;
use App\Modules\Identity\Application\Commands\CreateUserCommand;
use App\Modules\Identity\Application\Commands\UpdateUserCommand;
use App\Modules\Identity\Application\Commands\DeleteUserCommand;
use App\Modules\Identity\Application\Commands\LoginCommand;
use App\Modules\Identity\Application\Commands\ResetPasswordCommand;
use App\Modules\Identity\Application\Commands\RefreshTokenCommand;
use App\Modules\Identity\Application\Queries\GetUserByIdQuery;
use App\Modules\Identity\Application\Queries\GetAllUsersQuery;
use App\Modules\Identity\Application\Queries\GetAllRolesQuery;
use App\Modules\Identity\Application\Queries\GetUserPermissionsQuery;

class UserController
{
    public function __construct(
        protected CommandBus $commandBus,
        protected QueryBus $queryBus
    ) {}

    public function index(Request $request): array
    {
        $query = new GetAllUsersQuery();
        $users = $this->queryBus->dispatch($query);

        return [
            'success' => true,
            'data' => $users
        ];
    }

    public function show(string $id): array
    {
        $query = new GetUserByIdQuery($id);
        $user = $this->queryBus->dispatch($query);

        if (!$user) {
            return [
                'success' => false,
                'error' => 'User not found'
            ];
        }

        return [
            'success' => true,
            'data' => $user
        ];
    }

    public function store(Request $request): array
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required'
        ]);

        if (!empty($validated)) {
            return [
                'success' => false,
                'errors' => $validated
            ];
        }

        $command = new CreateUserCommand(
            name: $request->get('name'),
            email: $request->get('email'),
            password: $request->get('password'),
            role: $request->get('role'),
            userType: $request->get('user_type', 'staff')
        );

        $result = $this->commandBus->dispatch($command);

        return [
            'success' => true,
            'data' => $result
        ];
    }

    public function update(Request $request, string $id): array
    {
        $command = new UpdateUserCommand(
            id: $id,
            name: $request->get('name'),
            email: $request->get('email'),
            password: $request->get('password'),
            role: $request->get('role'),
            userType: $request->get('user_type')
        );

        $result = $this->commandBus->dispatch($command);

        return [
            'success' => true,
            'data' => $result
        ];
    }

    public function destroy(string $id): array
    {
        $command = new DeleteUserCommand($id);
        $this->commandBus->dispatch($command);

        return [
            'success' => true,
            'message' => 'User deleted successfully'
        ];
    }

    public function login(Request $request): array
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!empty($validated)) {
            return [
                'success' => false,
                'errors' => $validated
            ];
        }

        $command = new LoginCommand(
            email: $request->get('email'),
            password: $request->get('password')
        );

        $result = $this->commandBus->dispatch($command);

        return [
            'success' => true,
            'data' => $result
        ];
    }

    public function refreshToken(Request $request): array
    {
        $command = new RefreshTokenCommand(
            refreshToken: $request->get('refresh_token')
        );

        $result = $this->commandBus->dispatch($command);

        return [
            'success' => true,
            'data' => $result
        ];
    }

    public function getRoles(): array
    {
        $query = new GetAllRolesQuery();
        $roles = $this->queryBus->dispatch($query);

        return [
            'success' => true,
            'data' => $roles
        ];
    }

    public function getUserPermissions(string $id): array
    {
        $query = new GetUserPermissionsQuery($id);
        $permissions = $this->queryBus->dispatch($query);

        return [
            'success' => true,
            'data' => $permissions
        ];
    }
}
