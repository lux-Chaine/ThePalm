<?php

namespace App\Modules\Rooms\Infrastructure;

use App\Modules\Rooms\Domain\Room;
use App\Modules\Rooms\Domain\RoomRepositoryInterface;
use PDO;

class EloquentRoomRepository implements RoomRepositoryInterface
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

    public function findById(int $id): ?Room
    {
        $stmt = $this->db->prepare("SELECT * FROM rooms WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }

        return $this->mapToRoom($data);
    }

    public function findByRoomNumber(string $roomNumber): ?Room
    {
        $stmt = $this->db->prepare("SELECT * FROM rooms WHERE room_number = ? LIMIT 1");
        $stmt->execute([$roomNumber]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }

        return $this->mapToRoom($data);
    }

    public function findByType(string $type): array
    {
        $stmt = $this->db->prepare("SELECT * FROM rooms WHERE type = ?");
        $stmt->execute([$type]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToRoom'], $data);
    }

    public function findAvailable(): array
    {
        $stmt = $this->db->query("SELECT * FROM rooms WHERE status = 'available'");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToRoom'], $data);
    }

    public function findAvailableForDates(string $checkIn, string $checkOut): array
    {
        $stmt = $this->db->prepare(
            "SELECT DISTINCT r.* FROM rooms r 
             WHERE r.status = 'available' 
             AND r.id NOT IN (
                 SELECT room_id FROM reservations 
                 WHERE check_in <= ? AND check_out >= ? 
                 AND status IN ('confirmed', 'checked_in')
             )"
        );
        $stmt->execute([$checkOut, $checkIn]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToRoom'], $data);
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM rooms");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToRoom'], $data);
    }

    public function create(array $data): Room
    {
        $stmt = $this->db->prepare(
            "INSERT INTO rooms (room_number, type, price_per_night, status, floor, capacity, description, amenities, created_at, updated_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())"
        );
        
        $stmt->execute([
            $data['room_number'],
            $data['type'],
            $data['price_per_night'],
            $data['status'] ?? 'available',
            $data['floor'] ?? 1,
            $data['capacity'] ?? 2,
            $data['description'] ?? null,
            isset($data['amenities']) ? json_encode($data['amenities']) : null
        ]);

        $id = $this->db->lastInsertId();
        return $this->findById($id);
    }

    public function update(Room $room, array $data): Room
    {
        $stmt = $this->db->prepare(
            "UPDATE rooms SET room_number = ?, type = ?, price_per_night = ?, status = ?, 
             floor = ?, capacity = ?, description = ?, amenities = ?, updated_at = NOW() 
             WHERE id = ?"
        );
        
        $stmt->execute([
            $data['room_number'] ?? $room->roomNumber,
            $data['type'] ?? $room->type,
            $data['price_per_night'] ?? $room->pricePerNight,
            $data['status'] ?? $room->status,
            $data['floor'] ?? $room->floor,
            $data['capacity'] ?? $room->capacity,
            $data['description'] ?? $room->description,
            isset($data['amenities']) ? json_encode($data['amenities']) : json_encode($room->amenities),
            $room->id
        ]);

        return $this->findById($room->id);
    }

    public function delete(Room $room): bool
    {
        $stmt = $this->db->prepare("DELETE FROM rooms WHERE id = ?");
        return $stmt->execute([$room->id]);
    }

    public function countByType(string $type): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM rooms WHERE type = ?");
        $stmt->execute([$type]);
        return (int) $stmt->fetchColumn();
    }

    public function countByStatus(string $status): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM rooms WHERE status = ?");
        $stmt->execute([$status]);
        return (int) $stmt->fetchColumn();
    }

    private function mapToRoom(array $data): Room
    {
        return new Room([
            'id' => (int) $data['id'],
            'roomNumber' => $data['room_number'],
            'type' => $data['type'],
            'pricePerNight' => (float) $data['price_per_night'],
            'status' => $data['status'],
            'floor' => (int) $data['floor'],
            'capacity' => (int) $data['capacity'],
            'description' => $data['description'],
            'amenities' => isset($data['amenities']) ? json_decode($data['amenities'], true) : null,
            'createdAt' => $data['created_at'],
            'updatedAt' => $data['updated_at'],
        ]);
    }
}
