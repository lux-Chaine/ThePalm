<?php

namespace App\Modules\Reports\Presentation;

use App\Core\Bus\QueryBus;
use App\Core\Http\Request;
use App\Core\Http\ResponseFormatter;
use App\Modules\Reports\Application\Queries\GetFinancialReportQuery;
use App\Modules\Reports\Application\Queries\GetReservationReportQuery;
use App\Modules\Reports\Application\Queries\GetOccupancyReportQuery;

class ReportController
{
    public function __construct(
        protected QueryBus $queryBus
    ) {}

    public function financial(Request $request): array
    {
        $errors = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'type' => 'sometimes|in:income,expense,profit'
        ]);

        if (!empty($errors)) {
            return ResponseFormatter::validationError($errors);
        }

        $query = new GetFinancialReportQuery(
            startDate: $request->get('start_date'),
            endDate: $request->get('end_date'),
            type: $request->get('type')
        );

        $report = $this->queryBus->dispatch($query);

        return ResponseFormatter::item($report);
    }

    public function reservations(Request $request): array
    {
        $errors = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'sometimes|in:pending,confirmed,checked_in,checked_out,cancelled'
        ]);

        if (!empty($errors)) {
            return ResponseFormatter::validationError($errors);
        }

        $query = new GetReservationReportQuery(
            startDate: $request->get('start_date'),
            endDate: $request->get('end_date'),
            status: $request->get('status')
        );

        $report = $this->queryBus->dispatch($query);

        return ResponseFormatter::item($report);
    }

    public function occupancy(Request $request): array
    {
        $errors = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        if (!empty($errors)) {
            return ResponseFormatter::validationError($errors);
        }

        $query = new GetOccupancyReportQuery(
            startDate: $request->get('start_date'),
            endDate: $request->get('end_date')
        );

        $report = $this->queryBus->dispatch($query);

        return ResponseFormatter::item($report);
    }
}
