<?php

namespace App\Modules\Identity\Presentation;

use App\Core\Bus\CommandBus;
use App\Core\Bus\QueryBus;
use App\Modules\Identity\Application\Commands\CreateUserCommand;
use App\Modules\Identity\Application\Commands\UpdateUserCommand;
use App\Modules\Identity\Application\Commands\DeleteUserCommand;
use App\Modules\Identity\Application\Commands\LoginCommand;
use App\Modules\Identity\Application\Commands\ResetPasswordCommand;
use App\Modules\Identity\Application\Queries\GetUserByIdQuery;
use App\Modules\Identity\Application\Queries\GetAllUsersQuery;
use App\Modules\Identity\Application\Queries\GetAllRolesQuery;
use App\Modules\Identity\Application\Queries\GetUserPermissionsQuery;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController
{
    public function __construct(
        protected CommandBus $commandBus,
        protected QueryBus $queryBus
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = new GetAllUsersQuery(
            page: $request->get('page'),
            perPage: $request->get('per_page')
        );

        $users = $this->queryBus->dispatch($query);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $query = new GetUserByIdQuery($id);
        $user = $this->queryBus->dispatch($query);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'sometimes|in:admin,manager,receptionist,housekeeping,maintenance,accountant',
            'user_type' => 'sometimes|in:employee'
        ]);

        $command = new CreateUserCommand(
            name: $validated['name'],
            email: $validated['email'],
            password: $validated['password'],
            role: $validated['role'] ?? 'receptionist',
            userType: $validated['user_type'] ?? 'employee'
        );

        $user = $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'data' => $user
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8',
            'role' => 'sometimes|in:admin,manager,receptionist,housekeeping,maintenance,accountant',
            'user_type' => 'sometimes|in:employee'
        ]);

        $command = new UpdateUserCommand(
            userId: $id,
            name: $validated['name'] ?? null,
            email: $validated['email'] ?? null,
            password: $validated['password'] ?? null,
            role: $validated['role'] ?? null,
            userType: $validated['user_type'] ?? null
        );

        $user = $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $command = new DeleteUserCommand($id);
        $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $command = new LoginCommand(
            email: $validated['email'],
            password: $validated['password']
        );

        $result = $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    public function resetPassword(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'new_password' => 'required|string|min:8'
        ]);

        $command = new ResetPasswordCommand(
            userId: $id,
            newPassword: $validated['new_password']
        );

        $result = $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    public function updateRole(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,manager,receptionist,housekeeping,maintenance,accountant'
        ]);

        $command = new UpdateUserCommand(
            userId: $id,
            role: $validated['role']
        );

        $user = $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function getRoles(): JsonResponse
    {
        $query = new GetAllRolesQuery();
        $roles = $this->queryBus->dispatch($query);

        return response()->json([
            'success' => true,
            'data' => $roles
        ]);
    }

    public function getUserPermissions(int $id): JsonResponse
    {
        $query = new GetUserPermissionsQuery($id);
        $permissions = $this->queryBus->dispatch($query);

        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }
}
