<?php

namespace App\Core\Services;

use App\Modules\Accounting\Domain\Invoice;
use App\Modules\Accounting\Domain\InvoiceRepositoryInterface;
use App\Modules\Reservations\Domain\ReservationRepositoryInterface;
use App\Modules\Settings\Domain\SettingRepositoryInterface;

class InvoiceService
{
    private InvoiceRepositoryInterface $invoiceRepository;
    private ReservationRepositoryInterface $reservationRepository;
    private SettingRepositoryInterface $settingRepository;

    public function __construct(
        InvoiceRepositoryInterface $invoiceRepository,
        ReservationRepositoryInterface $reservationRepository,
        SettingRepositoryInterface $settingRepository
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->reservationRepository = $reservationRepository;
        $this->settingRepository = $settingRepository;
    }

    /**
     * Create invoice for a reservation
     */
    public function createInvoiceForReservation(int $reservationId, int $createdBy): Invoice
    {
        $reservation = $this->reservationRepository->findById($reservationId);
        
        if (!$reservation) {
            throw new \Exception("Reservation not found");
        }

        // Calculate invoice details
        $amount = $reservation->totalAmount ?? 0;
        $taxRate = $this->getSetting('tax_rate', 14);
        $taxAmount = $amount * ($taxRate / 100);
        
        // Check for discounts
        $discountAmount = $this->calculateDiscount($reservation);
        
        $totalAmount = $amount + $taxAmount - $discountAmount;

        // Set due date (7 days from now by default)
        $dueDate = date('Y-m-d', strtotime('+7 days'));

        $data = [
            'reservation_id' => $reservationId,
            'created_by' => $createdBy,
            'amount' => $totalAmount,
            'discount_amount' => $discountAmount,
            'tax_amount' => $taxAmount,
            'payment_status' => 'unpaid',
            'due_date' => $dueDate,
        ];

        return $this->invoiceRepository->create($data);
    }

    /**
     * Process payment for an invoice
     */
    public function processPayment(int $invoiceId, float $amount, string $paymentMethod): array
    {
        $invoice = $this->invoiceRepository->findById($invoiceId);
        
        if (!$invoice) {
            throw new \Exception("Invoice not found");
        }

        if ($invoice->isFullyPaid()) {
            throw new \Exception("Invoice is already fully paid");
        }

        $newPaidAmount = $invoice->paidAmount + $amount;
        $remainingAmount = $invoice->amount - $newPaidAmount;

        if ($newPaidAmount > $invoice->amount) {
            throw new \Exception("Payment amount exceeds remaining balance");
        }

        $paymentStatus = 'unpaid';
        if ($newPaidAmount >= $invoice->amount) {
            $paymentStatus = 'paid';
        } elseif ($newPaidAmount > 0) {
            $paymentStatus = 'partial';
        }

        $updatedInvoice = $this->invoiceRepository->update($invoice, [
            'paid_amount' => $newPaidAmount,
            'payment_status' => $paymentStatus,
            'payment_method' => $paymentMethod,
        ]);

        return [
            'success' => true,
            'invoice' => $updatedInvoice->toArray(),
            'payment_amount' => $amount,
            'remaining_amount' => max(0, $remainingAmount),
            'payment_status' => $paymentStatus,
        ];
    }

    /**
     * Calculate late fees for overdue invoices
     */
    public function calculateLateFees(int $invoiceId): array
    {
        $invoice = $this->invoiceRepository->findById($invoiceId);
        
        if (!$invoice) {
            throw new \Exception("Invoice not found");
        }

        if (!$invoice->isOverdue()) {
            return [
                'is_overdue' => false,
                'late_fee' => 0,
                'total_with_late_fee' => $invoice->amount,
            ];
        }

        $dailyRate = $this->getSetting('late_fee_daily_rate', 0.01);
        $lateFee = $invoice->calculateLateFee($dailyRate);
        $totalWithLateFee = $invoice->getTotalWithLateFee();

        return [
            'is_overdue' => true,
            'days_overdue' => $invoice->getOverdueDays(),
            'late_fee' => $lateFee,
            'total_with_late_fee' => $totalWithLateFee,
        ];
    }

    /**
     * Generate invoice summary
     */
    public function generateInvoiceSummary(int $invoiceId): array
    {
        $invoice = $this->invoiceRepository->findById($invoiceId);
        
        if (!$invoice) {
            throw new \Exception("Invoice not found");
        }

        $reservation = $this->reservationRepository->findById($invoice->reservationId);
        
        $summary = $invoice->getSummary();
        $summary['reservation'] = $reservation ? $reservation->toArray() : null;
        $summary['payment_schedule'] = $invoice->generatePaymentSchedule();
        $summary['late_fee_info'] = $this->calculateLateFees($invoiceId);

        return $summary;
    }

    /**
     * Get overdue invoices
     */
    public function getOverdueInvoices(): array
    {
        return $this->invoiceRepository->findOverdue();
    }

    /**
     * Get invoices due within a date range
     */
    public function getInvoicesDueBetween(string $startDate, string $endDate): array
    {
        return $this->invoiceRepository->findDueBetween($startDate, $endDate);
    }

    /**
     * Calculate discount based on reservation
     */
    private function calculateDiscount($reservation): float
    {
        $discount = 0;
        
        // Early payment discount (pay within 3 days)
        if ($reservation->status === 'confirmed') {
            $discountPercentage = $this->getSetting('early_payment_discount_percentage', 5);
            $discount = $reservation->totalAmount * ($discountPercentage / 100);
        }

        return round($discount, 2);
    }

    /**
     * Get setting value with default
     */
    private function getSetting(string $key, $default = null)
    {
        try {
            $setting = $this->settingRepository->findByKey($key);
            return $setting ? $setting->value : $default;
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Send invoice reminder
     */
    public function sendInvoiceReminder(int $invoiceId): array
    {
        $invoice = $this->invoiceRepository->findById($invoiceId);
        
        if (!$invoice) {
            throw new \Exception("Invoice not found");
        }

        if ($invoice->isFullyPaid()) {
            return [
                'success' => false,
                'message' => 'Invoice is already paid',
            ];
        }

        // In a real implementation, this would send an email
        // For now, we'll just return the reminder details
        $daysUntilDue = 0;
        if ($invoice->dueDate) {
            $dueDate = new \DateTime($invoice->dueDate);
            $now = new \DateTime();
            $daysUntilDue = $now->diff($dueDate)->days;
            if ($dueDate < $now) {
                $daysUntilDue = -$daysUntilDue;
            }
        }

        return [
            'success' => true,
            'message' => 'Reminder sent successfully',
            'invoice_id' => $invoice->id,
            'amount_due' => $invoice->getRemainingAmount(),
            'due_date' => $invoice->dueDate,
            'days_until_due' => $daysUntilDue,
            'is_overdue' => $invoice->isOverdue(),
        ];
    }

    /**
     * Cancel invoice
     */
    public function cancelInvoice(int $invoiceId, string $reason): Invoice
    {
        $invoice = $this->invoiceRepository->findById($invoiceId);
        
        if (!$invoice) {
            throw new \Exception("Invoice not found");
        }

        if (!$invoice->canBeCancelled()) {
            throw new \Exception("Invoice cannot be cancelled");
        }

        return $this->invoiceRepository->update($invoice, [
            'payment_status' => 'cancelled',
            'notes' => $reason,
        ]);
    }
}
