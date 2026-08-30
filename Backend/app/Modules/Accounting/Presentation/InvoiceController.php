<?php

namespace App\Modules\Accounting\Presentation;

use App\Core\Bus\CommandBus;
use App\Core\Bus\QueryBus;
use App\Core\Http\Request;
use App\Core\Http\ResponseFormatter;
use App\Core\Validation\Requests\CreateInvoiceRequest;
use App\Modules\Accounting\Application\Commands\CreateInvoiceCommand;
use App\Modules\Accounting\Application\Commands\UpdateInvoiceStatusCommand;
use App\Modules\Accounting\Application\Queries\GetInvoiceByIdQuery;
use App\Modules\Accounting\Application\Queries\GetAllInvoicesQuery;

class InvoiceController
{
    public function __construct(
        protected CommandBus $commandBus,
        protected QueryBus $queryBus
    ) {}

    public function index(Request $request): array
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

        return ResponseFormatter::collection($invoices);
    }

    public function show(int $id): array
    {
        $query = new GetInvoiceByIdQuery($id);
        $invoice = $this->queryBus->dispatch($query);

        if (!$invoice) {
            return ResponseFormatter::notFound('Invoice', $id);
        }

        return ResponseFormatter::item($invoice->toArray());
    }

    public function store(Request $request): array
    {
        $formRequest = new CreateInvoiceRequest($request);
        
        if (!$formRequest->validate()) {
            return ResponseFormatter::validationError($formRequest->allErrors());
        }

        $validated = $formRequest->getRequest()->all();

        $command = new CreateInvoiceCommand(
            reservationId: $validated['reservation_id'],
            createdBy: $validated['created_by'],
            amount: $validated['amount'],
            dueDate: $validated['due_date'] ?? null,
            discountAmount: $validated['discount_amount'] ?? null,
            taxAmount: $validated['tax_amount'] ?? null,
            paymentMethod: $validated['payment_method'] ?? null,
            notes: $validated['notes'] ?? null
        );

        $invoice = $this->commandBus->dispatch($command);

        return ResponseFormatter::created($invoice->toArray());
    }

    public function updatePayment(Request $request, int $id): array
    {
        $errors = $request->validate([
            'payment_status' => 'required|in:unpaid,partial,paid,overdue',
            'payment_amount' => 'sometimes|numeric|min:0',
            'payment_method' => 'sometimes|in:cash,credit_card,bank_transfer'
        ]);

        if (!empty($errors)) {
            return ResponseFormatter::validationError($errors);
        }

        $command = new UpdateInvoiceStatusCommand(
            invoiceId: $id,
            paymentStatus: $request->get('payment_status'),
            paymentAmount: $request->get('payment_amount'),
            paymentMethod: $request->get('payment_method')
        );

        $invoice = $this->commandBus->dispatch($command);

        return ResponseFormatter::updated($invoice->toArray());
    }
}
