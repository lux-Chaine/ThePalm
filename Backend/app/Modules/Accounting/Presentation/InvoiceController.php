<?php

namespace App\Modules\Accounting\Presentation;

use App\Core\Bus\CommandBus;
use App\Core\Bus\QueryBus;
use App\Modules\Accounting\Application\Commands\CreateInvoiceCommand;
use App\Modules\Accounting\Application\Commands\UpdateInvoiceStatusCommand;
use App\Modules\Accounting\Application\Queries\GetInvoiceByIdQuery;
use App\Modules\Accounting\Application\Queries\GetAllInvoicesQuery;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InvoiceController
{
    public function __construct(
        protected CommandBus $commandBus,
        protected QueryBus $queryBus
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = new GetAllInvoicesQuery(
            reservationId: $request->get('reservation_id'),
            status: $request->get('status'),
            startDate: $request->get('start_date'),
            endDate: $request->get('end_date'),
            page: $request->get('page'),
            perPage: $request->get('per_page')
        );

        $invoices = $this->queryBus->dispatch($query);

        return response()->json([
            'success' => true,
            'data' => $invoices
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $query = new GetInvoiceByIdQuery($id);
        $invoice = $this->queryBus->dispatch($query);

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'error' => 'Invoice not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $invoice
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reservation_id' => 'required|integer|exists:reservations,id',
            'created_by' => 'required|integer|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after:today',
            'discount_amount' => 'sometimes|numeric|min:0',
            'tax_amount' => 'sometimes|numeric|min:0',
            'payment_method' => 'sometimes|in:cash,credit_card,debit_card,bank_transfer',
            'notes' => 'sometimes|string'
        ]);

        $command = new CreateInvoiceCommand(
            reservationId: $validated['reservation_id'],
            createdBy: $validated['created_by'],
            amount: $validated['amount'],
            dueDate: $validated['due_date'],
            discountAmount: $validated['discount_amount'] ?? null,
            taxAmount: $validated['tax_amount'] ?? null,
            paymentMethod: $validated['payment_method'] ?? null,
            notes: $validated['notes'] ?? null
        );

        $invoice = $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'data' => $invoice->toArray()
        ], 201);
    }

    public function updatePayment(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:unpaid,partial,paid,overdue',
            'payment_amount' => 'sometimes|numeric|min:0',
            'payment_method' => 'sometimes|in:cash,credit_card,debit_card,bank_transfer'
        ]);

        $command = new UpdateInvoiceStatusCommand(
            invoiceId: $id,
            paymentStatus: $validated['payment_status'],
            paymentAmount: $validated['payment_amount'] ?? null,
            paymentMethod: $validated['payment_method'] ?? null
        );

        $invoice = $this->commandBus->dispatch($command);

        return response()->json([
            'success' => true,
            'data' => $invoice->toArray()
        ]);
    }
}
