<?php

namespace App\Core\BusinessRules;

use App\Modules\Reservations\Domain\Reservation;
use App\Modules\Rooms\Domain\Room;
use App\Modules\Accounting\Domain\Invoice;
use App\Modules\Accounting\Domain\Expense;

class BusinessRulesEngine
{
    private array $rules = [];

    public function __construct()
    {
        $this->initializeRules();
    }

    /**
     * Initialize all business rules
     */
    private function initializeRules(): void
    {
        // Reservation rules
        $this->rules['reservation'] = [
            'minimum_nights' => 1,
            'maximum_nights' => 30,
            'advance_booking_days' => 365,
            'cancellation_deadline_hours' => 24,
            'confirmation_deadline_hours' => 24,
        ];

        // Room rules
        $this->rules['room'] = [
            'minimum_capacity' => 1,
            'maximum_capacity' => 6,
            'maintenance_interval_days' => 90,
            'cleaning_duration_hours' => 2,
        ];

        // Payment rules
        $this->rules['payment'] = [
            'deposit_required_nights' => 3,
            'deposit_percentage' => 20,
            'full_payment_deadline_hours' => 48,
            'late_fee_daily_rate' => 0.01,
        ];

        // Discount rules
        $this->rules['discount'] = [
            'long_stay_threshold_nights' => 7,
            'long_stay_discount_percentage' => 10,
            'group_discount_min_guests' => 3,
            'group_discount_percentage' => 5,
            'early_booking_days' => 30,
            'early_booking_discount_percentage' => 5,
        ];

        // Expense rules
        $this->rules['expense'] = [
            'approval_threshold_amount' => 1000,
            'capital_expense_threshold' => 10000,
        ];
    }

    /**
     * Validate reservation against business rules
     */
    public function validateReservation(array $data): array
    {
        $errors = [];
        $warnings = [];

        // Check minimum nights
        if (isset($data['check_in_date'], $data['check_out_date'])) {
            $checkIn = new \DateTime($data['check_in_date']);
            $checkOut = new \DateTime($data['check_out_date']);
            $duration = (int) $checkIn->diff($checkOut)->days;

            if ($duration < $this->rules['reservation']['minimum_nights']) {
                $errors[] = "Minimum stay is {$this->rules['reservation']['minimum_nights']} night(s)";
            }

            if ($duration > $this->rules['reservation']['maximum_nights']) {
                $errors[] = "Maximum stay is {$this->rules['reservation']['maximum_nights']} nights";
            }

            // Check advance booking limit
            $now = new \DateTime();
            $daysInAdvance = (int) $now->diff($checkIn)->format('%r%a');
            if ($daysInAdvance > $this->rules['reservation']['advance_booking_days']) {
                $errors[] = "Bookings cannot be made more than {$this->rules['reservation']['advance_booking_days']} days in advance";
            }

            // Check if check-in is in the past
            if ($checkIn < $now) {
                $errors[] = "Check-in date cannot be in the past";
            }

            // Check if check-out is before check-in
            if ($checkOut <= $checkIn) {
                $errors[] = "Check-out date must be after check-in date";
            }
        }

        // Check guest count
        if (isset($data['number_of_guests'])) {
            if ($data['number_of_guests'] < 1) {
                $errors[] = "At least 1 guest is required";
            }

            if (isset($data['room_id'])) {
                // This would need room repository to check capacity
                // For now, we'll use general rules
                if ($data['number_of_guests'] > $this->rules['room']['maximum_capacity']) {
                    $errors[] = "Maximum {$this->rules['room']['maximum_capacity']} guests allowed per room";
                }
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }

    /**
     * Check if reservation can be cancelled
     */
    public function canCancelReservation(Reservation $reservation): array
    {
        if ($reservation->status === 'cancelled') {
            return [
                'can_cancel' => false,
                'reason' => 'Reservation is already cancelled',
            ];
        }

        if ($reservation->status === 'completed') {
            return [
                'can_cancel' => false,
                'reason' => 'Cannot cancel completed reservations',
            ];
        }

        if ($reservation->status === 'checked_in') {
            return [
                'can_cancel' => false,
                'reason' => 'Cannot cancel checked-in reservations',
            ];
        }

        // Check cancellation deadline
        $checkIn = new \DateTime($reservation->checkInDate);
        $now = new \DateTime();
        $hoursUntilCheckIn = (int) $now->diff($checkIn)->format('%r%h');

        if ($hoursUntilCheckIn < $this->rules['reservation']['cancellation_deadline_hours']) {
            $cancellationFee = $this->calculateCancellationFee($reservation);
            return [
                'can_cancel' => true,
                'with_fee' => true,
                'fee_amount' => $cancellationFee,
                'reason' => 'Cancellation fee applies due to short notice',
            ];
        }

        return [
            'can_cancel' => true,
            'with_fee' => false,
            'fee_amount' => 0,
            'reason' => 'Free cancellation available',
        ];
    }

    /**
     * Calculate cancellation fee
     */
    public function calculateCancellationFee(Reservation $reservation): float
    {
        $totalAmount = $reservation->totalAmount ?? 0;
        $feePercentage = $this->getSetting('cancellation_fee_percentage', 50);
        return $totalAmount * ($feePercentage / 100);
    }

    /**
     * Check if deposit is required for reservation
     */
    public function isDepositRequired(Reservation $reservation): bool
    {
        $checkIn = new \DateTime($reservation->checkInDate);
        $checkOut = new \DateTime($reservation->checkOutDate);
        $duration = (int) $checkIn->diff($checkOut)->days;

        return $duration >= $this->rules['payment']['deposit_required_nights'];
    }

    /**
     * Calculate deposit amount
     */
    public function calculateDepositAmount(Reservation $reservation): float
    {
        if (!$this->isDepositRequired($reservation)) {
            return 0;
        }

        $totalAmount = $reservation->totalAmount ?? 0;
        $depositPercentage = $this->rules['payment']['deposit_percentage'];
        return $totalAmount * ($depositPercentage / 100);
    }

    /**
     * Check if expense requires approval
     */
    public function requiresExpenseApproval(Expense $expense): bool
    {
        return $expense->amount >= $this->rules['expense']['approval_threshold_amount'];
    }

    /**
     * Check if expense is capital expenditure
     */
    public function isCapitalExpense(Expense $expense): bool
    {
        return $expense->amount >= $this->rules['expense']['capital_expense_threshold'];
    }

    /**
     * Calculate applicable discounts for reservation
     */
    public function calculateApplicableDiscounts(array $data): array
    {
        $discounts = [];
        $totalDiscount = 0;

        // Long stay discount
        if (isset($data['check_in_date'], $data['check_out_date'])) {
            $checkIn = new \DateTime($data['check_in_date']);
            $checkOut = new \DateTime($data['check_out_date']);
            $duration = (int) $checkIn->diff($checkOut)->days;

            if ($duration >= $this->rules['discount']['long_stay_threshold_nights']) {
                $discountPercentage = $this->rules['discount']['long_stay_discount_percentage'];
                $discounts[] = [
                    'type' => 'long_stay',
                    'description' => 'Long stay discount',
                    'percentage' => $discountPercentage,
                ];
                $totalDiscount += $discountPercentage;
            }
        }

        // Group discount
        if (isset($data['number_of_guests'])) {
            if ($data['number_of_guests'] >= $this->rules['discount']['group_discount_min_guests']) {
                $discountPercentage = $this->rules['discount']['group_discount_percentage'];
                $discounts[] = [
                    'type' => 'group',
                    'description' => 'Group discount',
                    'percentage' => $discountPercentage,
                ];
                $totalDiscount += $discountPercentage;
            }
        }

        // Early booking discount
        if (isset($data['check_in_date'])) {
            $checkIn = new \DateTime($data['check_in_date']);
            $now = new \DateTime();
            $daysInAdvance = (int) $now->diff($checkIn)->format('%r%a');

            if ($daysInAdvance >= $this->rules['discount']['early_booking_days']) {
                $discountPercentage = $this->rules['discount']['early_booking_discount_percentage'];
                $discounts[] = [
                    'type' => 'early_booking',
                    'description' => 'Early booking discount',
                    'percentage' => $discountPercentage,
                ];
                $totalDiscount += $discountPercentage;
            }
        }

        return [
            'discounts' => $discounts,
            'total_discount_percentage' => min($totalDiscount, 50), // Cap at 50%
        ];
    }

    /**
     * Check if room can be booked
     */
    public function canBookRoom(Room $room, array $data): array
    {
        $issues = [];

        if ($room->status !== 'available') {
            $issues[] = "Room is not available (current status: {$room->status})";
        }

        if (isset($data['number_of_guests'])) {
            if ($data['number_of_guests'] > $room->capacity) {
                $issues[] = "Room capacity exceeded (max: {$room->capacity})";
            }
        }

        return [
            'can_book' => empty($issues),
            'issues' => $issues,
        ];
    }

    /**
     * Check if invoice is overdue
     */
    public function isInvoiceOverdue(Invoice $invoice): bool
    {
        if (!$invoice->dueDate) {
            return false;
        }

        $dueDate = new \DateTime($invoice->dueDate);
        $now = new \DateTime();

        return $dueDate < $now && $invoice->paymentStatus !== 'paid';
    }

    /**
     * Calculate late fee for invoice
     */
    public function calculateInvoiceLateFee(Invoice $invoice): float
    {
        if (!$this->isInvoiceOverdue($invoice)) {
            return 0;
        }

        $dueDate = new \DateTime($invoice->dueDate);
        $now = new \DateTime();
        $daysOverdue = (int) $now->diff($dueDate)->days;

        $dailyRate = $this->rules['payment']['late_fee_daily_rate'];
        $remainingAmount = $invoice->getRemainingAmount();

        return $remainingAmount * $dailyRate * $daysOverdue;
    }

    /**
     * Get business rule value
     */
    public function getRule(string $category, string $key, $default = null)
    {
        return $this->rules[$category][$key] ?? $default;
    }

    /**
     * Set business rule value
     */
    public function setRule(string $category, string $key, $value): void
    {
        if (!isset($this->rules[$category])) {
            $this->rules[$category] = [];
        }

        $this->rules[$category][$key] = $value;
    }

    /**
     * Get all rules for a category
     */
    public function getCategoryRules(string $category): array
    {
        return $this->rules[$category] ?? [];
    }

    /**
     * Get all rules
     */
    public function getAllRules(): array
    {
        return $this->rules;
    }

    /**
     * Get setting value (helper method)
     */
    private function getSetting(string $key, $default = null)
    {
        // This would typically fetch from settings repository
        // For now, return default
        return $default;
    }
}
