<?php

namespace App\Modules\Accounting\Presentation;

use App\Core\Bus\CommandBus;
use App\Core\Bus\QueryBus;
use App\Modules\Accounting\Application\Commands\CreateExpenseCommand;
use App\Modules\Accounting\Application\Commands\UpdateExpenseStatusCommand;
use App\Modules\Accounting\Application\Queries\GetExpenseByIdQuery;
use App\Modules\Accounting\Application\Queries\GetAllExpensesQuery;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExpenseController
{
    public function __construct(
        protected CommandBus $commandBus,
        protected QueryBus $queryBus
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = new GetAllExpensesQuery(
            category: $request->get('category'),
            status: $request->get('status'),
            startDate: $request->get('start_date'),
            endDate: $request->get('end_date'),
            page: $request->get('page'),
            perPage: $request->get('per_page')
        );

        $expenses = $this->queryBus->dispatch($query);

        return response()->json([
            'success' => true,
            'data' => $expenses
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $query = new GetExpenseByIdQuery($id);
        $expense = $this->queryBus->dispatch($query);

        if (!$expense) {
            return response()->json([
                'success' => false,
                'error' => 'Expense not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $expense
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'created_by' => 'required|integer|exists:users,id',
            'category' => 'required|in:salaries,utilities,insurance,maintenance,supplies,cleaning,food_beverage,marketing,laundry,other',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'receipt_url' => 'sometimes|url',
            'notes' => 'sometimes|string'
        ]);

        $command = new CreateExpenseCommand(
            createdBy: $validated['created_by'],
            category: $validated['category'],
            description: $validated['description'],
            amount: $validated['amount'],
            expenseDate: $validated['expense_date'],
            receiptUrl: $validated['receipt_url'] ?? null,
            notes: $validated['notes'] ?? null
        );

        $expense = $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'data' => $expense->toArray()
        ], 201);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,paid',
            'rejection_reason' => 'sometimes|string'
        ]);

        $command = new UpdateExpenseStatusCommand(
            expenseId: $id,
            status: $validated['status'],
            rejectionReason: $validated['rejection_reason'] ?? null
        );

        $expense = $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'data' => $expense->toArray()
        ]);
    }
}
