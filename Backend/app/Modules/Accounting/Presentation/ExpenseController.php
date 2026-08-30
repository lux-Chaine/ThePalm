<?php

namespace App\Modules\Accounting\Presentation;

use App\Core\Bus\CommandBus;
use App\Core\Bus\QueryBus;
use App\Core\Http\Request;
use App\Core\Http\ResponseFormatter;
use App\Core\Validation\Requests\CreateExpenseRequest;
use App\Modules\Accounting\Application\Commands\CreateExpenseCommand;
use App\Modules\Accounting\Application\Commands\UpdateExpenseStatusCommand;
use App\Modules\Accounting\Application\Queries\GetExpenseByIdQuery;
use App\Modules\Accounting\Application\Queries\GetAllExpensesQuery;

class ExpenseController
{
    public function __construct(
        protected CommandBus $commandBus,
        protected QueryBus $queryBus
    ) {}

    public function index(Request $request): array
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

        return ResponseFormatter::collection($expenses);
    }

    public function show(int $id): array
    {
        $query = new GetExpenseByIdQuery($id);
        $expense = $this->queryBus->dispatch($query);

        if (!$expense) {
            return ResponseFormatter::notFound('Expense', $id);
        }

        return ResponseFormatter::item($expense->toArray());
    }

    public function store(Request $request): array
    {
        $formRequest = new CreateExpenseRequest($request);
        
        if (!$formRequest->validate()) {
            return ResponseFormatter::validationError($formRequest->allErrors());
        }

        $validated = $formRequest->getRequest()->all();

        $command = new CreateExpenseCommand(
            createdBy: $validated['created_by'],
            category: $validated['category'],
            description: $validated['description'],
            amount: $validated['amount'],
            expenseDate: $validated['expense_date'] ?? null,
            status: $validated['status'] ?? 'pending',
            notes: $validated['notes'] ?? null
        );

        $expense = $this->commandBus->dispatch($command);

        return ResponseFormatter::created($expense->toArray());
    }

    public function updateStatus(Request $request, int $id): array
    {
        $errors = $request->validate([
            'status' => 'required|in:pending,approved,rejected,paid',
            'rejection_reason' => 'sometimes|string'
        ]);

        if (!empty($errors)) {
            return ResponseFormatter::validationError($errors);
        }

        $command = new UpdateExpenseStatusCommand(
            expenseId: $id,
            status: $request->get('status'),
            rejectionReason: $request->get('rejection_reason')
        );

        $expense = $this->commandBus->dispatch($command);

        return ResponseFormatter::updated($expense->toArray());
    }
}
