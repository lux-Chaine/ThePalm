<?php

namespace App\Modules\Identity\Presentation;

use App\Core\Bus\CommandBus;
use App\Core\Bus\QueryBus;
use App\Core\Http\Request;
use App\Core\Http\ResponseFormatter;
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

        return ResponseFormatter::collection($users);
    }

    public function show(string $id): array
    {
        $query = new GetUserByIdQuery($id);
        $user = $this->queryBus->dispatch($query);

        if (!$user) {
            return ResponseFormatter::notFound('User', $id);
        }

        return ResponseFormatter::item($user);
    }

    public function store(Request $request): array
    {
        $formRequest = new App\Core\Validation\Requests\CreateUserRequest($request);
        
        if (!$formRequest->validate()) {
            return ResponseFormatter::validationError($formRequest->allErrors());
        }

        $validated = $formRequest->getRequest()->all();

        $command = new CreateUserCommand(
            name: $validated['name'],
            email: $validated['email'],
            password: $validated['password'],
            role: $validated['role'],
            userType: $validated['user_type'] ?? 'staff'
        );

        $result = $this->commandBus->dispatch($command);

        return ResponseFormatter::created($result);
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

        return ResponseFormatter::updated($result);
    }

    public function destroy(string $id): array
    {
        $command = new DeleteUserCommand($id);
        $this->commandBus->dispatch($command);

        return ResponseFormatter::deleted();
    }

    public function login(Request $request): array
    {
        $formRequest = new App\Core\Validation\Requests\LoginRequest($request);
        
        if (!$formRequest->validate()) {
            return ResponseFormatter::validationError($formRequest->allErrors());
        }

        $validated = $formRequest->getRequest()->all();

        $command = new LoginCommand(
            email: $validated['email'],
            password: $validated['password']
        );

        $result = $this->commandBus->dispatch($command);

        return ResponseFormatter::success($result, 'Login successful');
    }

    public function refreshToken(Request $request): array
    {
        $command = new RefreshTokenCommand(
            refreshToken: $request->get('refresh_token')
        );

        $result = $this->commandBus->dispatch($command);

        return ResponseFormatter::success($result, 'Token refreshed successfully');
    }

    public function getRoles(): array
    {
        $query = new GetAllRolesQuery();
        $roles = $this->queryBus->dispatch($query);

        return ResponseFormatter::collection($roles);
    }

    public function getUserPermissions(string $id): array
    {
        $query = new GetUserPermissionsQuery($id);
        $permissions = $this->queryBus->dispatch($query);

        return ResponseFormatter::item($permissions);
    }
}
