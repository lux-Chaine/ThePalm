<?php

namespace App\Modules\Reservations\Infrastructure;

use App\Modules\Reservations\Domain\Reservation;
use App\Modules\Reservations\Domain\ReservationRepositoryInterface;
use PDO;

class EloquentReservationRepository implements ReservationRepositoryInterface
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

    public function findById(int $id): ?Reservation
    {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }

        return $this->mapToReservation($data);
    }

    public function findByGuestId(int $guestId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE guest_id = ?");
        $stmt->execute([$guestId]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToReservation'], $data);
    }

    public function findByRoomId(int $roomId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE room_id = ?");
        $stmt->execute([$roomId]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToReservation'], $data);
    }

    public function findByUserId(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE user_id = ?");
        $stmt->execute([$userId]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToReservation'], $data);
    }

    public function findByStatus(string $status): array
    {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE status = ?");
        $stmt->execute([$status]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToReservation'], $data);
    }

    public function findActive(): array
    {
        $stmt = $this->db->query("SELECT * FROM reservations WHERE status IN ('confirmed', 'checked_in')");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToReservation'], $data);
    }

    public function findForDateRange(string $startDate, string $endDate): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM reservations WHERE check_in >= ? AND check_out <= ?"
        );
        $stmt->execute([$startDate, $endDate]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToReservation'], $data);
    }

    public function findConflictingReservations(int $roomId, string $checkIn, string $checkOut): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM reservations WHERE room_id = ? AND status != 'cancelled' 
             AND check_in < ? AND check_out > ?"
        );
        $stmt->execute([$roomId, $checkOut, $checkIn]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToReservation'], $data);
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM reservations");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToReservation'], $data);
    }

    public function create(array $data): Reservation
    {
        $stmt = $this->db->prepare(
            "INSERT INTO reservations (guest_id, room_id, user_id, check_in_date, check_out_date, number_of_guests, special_requests, status, total_amount, created_at, updated_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())"
        );
        
        $stmt->execute([
            $data['guest_id'],
            $data['room_id'],
            $data['user_id'],
            $data['check_in_date'],
            $data['check_out_date'],
            $data['number_of_guests'] ?? 1,
            $data['special_requests'] ?? null,
            $data['status'] ?? 'pending',
            $data['total_amount'] ?? null
        ]);

        $id = $this->db->lastInsertId();
        return $this->findById($id);
    }

    public function update(Reservation $reservation, array $data): Reservation
    {
        $stmt = $this->db->prepare(
            "UPDATE reservations SET guest_id = ?, room_id = ?, user_id = ?, check_in_date = ?, check_out_date = ?, 
             number_of_guests = ?, special_requests = ?, status = ?, cancellation_reason = ?, total_amount = ?, updated_at = NOW() 
             WHERE id = ?"
        );
        
        $stmt->execute([
            $data['guest_id'] ?? $reservation->guestId,
            $data['room_id'] ?? $reservation->roomId,
            $data['user_id'] ?? $reservation->userId,
            $data['check_in_date'] ?? $reservation->checkInDate,
            $data['check_out_date'] ?? $reservation->checkOutDate,
            $data['number_of_guests'] ?? $reservation->numberOfGuests,
            $data['special_requests'] ?? $reservation->specialRequests,
            $data['status'] ?? $reservation->status,
            $data['cancellation_reason'] ?? $reservation->cancellationReason,
            $data['total_amount'] ?? $reservation->totalAmount,
            $reservation->id
        ]);

        return $this->findById($reservation->id);
    }

    public function delete(Reservation $reservation): bool
    {
        $stmt = $this->db->prepare("DELETE FROM reservations WHERE id = ?");
        return $stmt->execute([$reservation->id]);
    }

    public function countByStatus(string $status): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM reservations WHERE status = ?");
        $stmt->execute([$status]);
        return (int) $stmt->fetchColumn();
    }

    public function getRevenueForPeriod(string $startDate, string $endDate): float
    {
        $stmt = $this->db->prepare(
            "SELECT SUM(total_amount) FROM reservations 
             WHERE check_in_date >= ? AND check_out_date <= ? AND status != 'cancelled'"
        );
        $stmt->execute([$startDate, $endDate]);
        return (float) ($stmt->fetchColumn() ?: 0);
    }

    private function mapToReservation(array $data): Reservation
    {
        return new Reservation([
            'id' => (int) $data['id'],
            'guestId' => (int) $data['guest_id'],
            'roomId' => (int) $data['room_id'],
            'userId' => (int) $data['user_id'],
            'checkInDate' => $data['check_in_date'],
            'checkOutDate' => $data['check_out_date'],
            'numberOfGuests' => (int) $data['number_of_guests'],
            'specialRequests' => $data['special_requests'],
            'status' => $data['status'],
            'cancellationReason' => $data['cancellation_reason'],
            'totalAmount' => $data['total_amount'] ? (float) $data['total_amount'] : null,
            'createdAt' => $data['created_at'],
            'updatedAt' => $data['updated_at'],
        ]);
    }
}
