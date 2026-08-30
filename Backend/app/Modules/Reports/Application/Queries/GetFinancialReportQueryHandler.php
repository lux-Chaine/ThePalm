<?php

namespace App\Modules\Reports\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Core\Bus\QueryInterface;
use App\Modules\Accounting\Domain\InvoiceRepositoryInterface;
use App\Modules\Accounting\Domain\ExpenseRepositoryInterface;

class GetFinancialReportQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected InvoiceRepositoryInterface $invoiceRepository,
        protected ExpenseRepositoryInterface $expenseRepository
    ) {}

    public function handle(QueryInterface $query): array
    {
        $invoices = $this->invoiceRepository->all();
        $expenses = $this->expenseRepository->all();

        // Filter by date range
        $filteredInvoices = $invoices->filter(function ($invoice) use ($query) {
            return $invoice->created_at >= $query->startDate && $invoice->created_at <= $query->endDate;
        });

        $filteredExpenses = $expenses->filter(function ($expense) use ($query) {
            return $expense->created_at >= $query->startDate && $expense->created_at <= $query->endDate;
        });

        // Calculate totals
        $totalIncome = $filteredInvoices->sum('amount');
        $totalExpense = $filteredExpenses->sum('amount');
        $profit = $totalIncome - $totalExpense;

        return [
            'period' => [
                'start_date' => $query->startDate,
                'end_date' => $query->endDate
            ],
            'income' => [
                'total' => $totalIncome,
                'count' => $filteredInvoices->count(),
                'invoices' => $filteredInvoices->toArray()
            ],
            'expenses' => [
                'total' => $totalExpense,
                'count' => $filteredExpenses->count(),
                'expenses' => $filteredExpenses->toArray()
            ],
            'profit' => $profit,
            'profit_margin' => $totalIncome > 0 ? ($profit / $totalIncome) * 100 : 0
        ];
    }
}
