<?php

namespace App\Modules\Reports\Presentation;

use App\Core\Bus\QueryBus;
use App\Modules\Reports\Application\Queries\GetFinancialReportQuery;
use App\Modules\Reports\Application\Queries\GetReservationReportQuery;
use App\Modules\Reports\Application\Queries\GetOccupancyReportQuery;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReportController
{
    public function __construct(
        protected QueryBus $queryBus
    ) {}

    public function financial(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'sometimes|in:income,expense,profit'
        ]);

        $query = new GetFinancialReportQuery(
            startDate: $validated['start_date'],
            endDate: $validated['end_date'],
            type: $validated['type'] ?? null
        );

        $report = $this->queryBus->dispatch($query);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    public function reservations(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'sometimes|in:pending,confirmed,checked_in,checked_out,cancelled'
        ]);

        $query = new GetReservationReportQuery(
            startDate: $validated['start_date'],
            endDate: $validated['end_date'],
            status: $validated['status'] ?? null
        );

        $report = $this->queryBus->dispatch($query);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    public function occupancy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $query = new GetOccupancyReportQuery(
            startDate: $validated['start_date'],
            endDate: $validated['end_date']
        );

        $report = $this->queryBus->dispatch($query);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }
}
