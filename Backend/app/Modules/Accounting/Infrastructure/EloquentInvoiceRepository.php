<?php

namespace App\Modules\Accounting\Infrastructure;

use App\Modules\Accounting\Domain\Invoice;
use App\Modules\Accounting\Domain\InvoiceRepositoryInterface;
use PDO;

class EloquentInvoiceRepository implements InvoiceRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = new PDO(
            'mysql:host=localhost;dbname=palm_hotel;charset=utf8mb4',
            'root',
            '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public function findById(int $id): ?Invoice
    {
        $stmt = $this->db->prepare("SELECT * FROM invoices WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }

        return $this->mapToInvoice($data);
    }

    public function findByInvoiceNumber(string $invoiceNumber): ?Invoice
    {
        $stmt = $this->db->prepare("SELECT * FROM invoices WHERE invoice_number = ? LIMIT 1");
        $stmt->execute([$invoiceNumber]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }

        return $this->mapToInvoice($data);
    }

    public function findByReservationId(int $reservationId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM invoices WHERE reservation_id = ?");
        $stmt->execute([$reservationId]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToInvoice'], $data);
    }

    public function findByStatus(string $status): array
    {
        $stmt = $this->db->prepare("SELECT * FROM invoices WHERE payment_status = ?");
        $stmt->execute([$status]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToInvoice'], $data);
    }

    public function findOverdue(): array
    {
        $stmt = $this->db->query("SELECT * FROM invoices WHERE due_date < NOW() AND payment_status != 'paid'");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToInvoice'], $data);
    }

    public function findDueBetween(string $startDate, string $endDate): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM invoices WHERE due_date BETWEEN ? AND ? AND payment_status != 'paid'"
        );
        $stmt->execute([$startDate, $endDate]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToInvoice'], $data);
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM invoices");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToInvoice'], $data);
    }

    public function create(array $data): Invoice
    {
        $stmt = $this->db->prepare(
            "INSERT INTO invoices (reservation_id, created_by, amount, due_date, discount_amount, tax_amount, payment_method, payment_status, paid_amount, notes, created_at, updated_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())"
        );
        
        $stmt->execute([
            $data['reservation_id'],
            $data['created_by'],
            $data['amount'],
            $data['due_date'] ?? null,
            $data['discount_amount'] ?? null,
            $data['tax_amount'] ?? null,
            $data['payment_method'] ?? null,
            $data['payment_status'] ?? 'unpaid',
            $data['paid_amount'] ?? 0,
            $data['notes'] ?? null
        ]);

        $id = $this->db->lastInsertId();
        return $this->findById($id);
    }

    public function update(Invoice $invoice, array $data): Invoice
    {
        $stmt = $this->db->prepare(
            "UPDATE invoices SET reservation_id = ?, created_by = ?, amount = ?, due_date = ?, 
             discount_amount = ?, tax_amount = ?, payment_method = ?, payment_status = ?, 
             paid_amount = ?, notes = ?, updated_at = NOW() WHERE id = ?"
        );
        
        $stmt->execute([
            $data['reservation_id'] ?? $invoice->reservationId,
            $data['created_by'] ?? $invoice->createdBy,
            $data['amount'] ?? $invoice->amount,
            $data['due_date'] ?? $invoice->dueDate,
            $data['discount_amount'] ?? $invoice->discountAmount,
            $data['tax_amount'] ?? $invoice->taxAmount,
            $data['payment_method'] ?? $invoice->paymentMethod,
            $data['payment_status'] ?? $invoice->paymentStatus,
            $data['paid_amount'] ?? $invoice->paidAmount,
            $data['notes'] ?? $invoice->notes,
            $invoice->id
        ]);

        return $this->findById($invoice->id);
    }

    public function delete(Invoice $invoice): bool
    {
        $stmt = $this->db->prepare("DELETE FROM invoices WHERE id = ?");
        return $stmt->execute([$invoice->id]);
    }

    public function getTotalRevenue(string $startDate, string $endDate): float
    {
        $stmt = $this->db->prepare(
            "SELECT SUM(amount) FROM invoices WHERE created_at >= ? AND created_at <= ?"
        );
        $stmt->execute([$startDate, $endDate]);
        return (float) ($stmt->fetchColumn() ?: 0);
    }

    public function getOutstandingAmount(): float
    {
        $stmt = $this->db->query(
            "SELECT SUM(amount) - SUM(paid_amount) FROM invoices WHERE payment_status != 'paid'"
        );
        return (float) ($stmt->fetchColumn() ?: 0);
    }

    public function getOverdueAmount(): float
    {
        $stmt = $this->db->query(
            "SELECT SUM(amount) - SUM(paid_amount) FROM invoices WHERE due_date < NOW() AND payment_status != 'paid'"
        );
        return (float) ($stmt->fetchColumn() ?: 0);
    }

    private function mapToInvoice(array $data): Invoice
    {
        return new Invoice([
            'id' => (int) $data['id'],
            'reservationId' => (int) $data['reservation_id'],
            'createdBy' => (int) $data['created_by'],
            'amount' => (float) $data['amount'],
            'dueDate' => $data['due_date'],
            'discountAmount' => $data['discount_amount'] ? (float) $data['discount_amount'] : null,
            'taxAmount' => $data['tax_amount'] ? (float) $data['tax_amount'] : null,
            'paymentMethod' => $data['payment_method'],
            'paymentStatus' => $data['payment_status'],
            'paidAmount' => (float) ($data['paid_amount'] ?? 0),
            'notes' => $data['notes'],
            'createdAt' => $data['created_at'],
            'updatedAt' => $data['updated_at'],
        ]);
    }
}
