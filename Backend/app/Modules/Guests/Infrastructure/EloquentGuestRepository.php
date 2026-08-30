<?php

namespace App\Modules\Guests\Infrastructure;

use App\Modules\Guests\Domain\Guest;
use App\Modules\Guests\Domain\GuestRepositoryInterface;
use PDO;

class EloquentGuestRepository implements GuestRepositoryInterface
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

    public function findById(int $id): ?Guest
    {
        $stmt = $this->db->prepare("SELECT * FROM guests WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }

        return $this->mapToGuest($data);
    }

    public function findByEmail(string $email): ?Guest
    {
        $stmt = $this->db->prepare("SELECT * FROM guests WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }

        return $this->mapToGuest($data);
    }

    public function findByIdentityNumber(string $identityNumber): ?Guest
    {
        $stmt = $this->db->prepare("SELECT * FROM guests WHERE identity_number = ? LIMIT 1");
        $stmt->execute([$identityNumber]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }

        return $this->mapToGuest($data);
    }

    public function findByPhone(string $phone): ?Guest
    {
        $stmt = $this->db->prepare("SELECT * FROM guests WHERE phone = ? LIMIT 1");
        $stmt->execute([$phone]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }

        return $this->mapToGuest($data);
    }

    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM guests");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToGuest'], $data);
    }

    public function create(array $data): Guest
    {
        $stmt = $this->db->prepare(
            "INSERT INTO guests (name, email, phone, identity_number, identity_type, date_of_birth, address, city, country, notes, created_at, updated_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())"
        );
        
        $stmt->execute([
            $data['name'],
            $data['email'] ?? null,
            $data['phone'],
            $data['identity_number'] ?? null,
            $data['identity_type'] ?? 'national_id',
            $data['date_of_birth'] ?? null,
            $data['address'] ?? null,
            $data['city'] ?? null,
            $data['country'] ?? 'Egypt',
            $data['notes'] ?? null
        ]);

        $id = $this->db->lastInsertId();
        return $this->findById($id);
    }

    public function update(Guest $guest, array $data): Guest
    {
        $stmt = $this->db->prepare(
            "UPDATE guests SET name = ?, email = ?, phone = ?, identity_number = ?, identity_type = ?, 
             date_of_birth = ?, address = ?, city = ?, country = ?, notes = ?, updated_at = NOW() 
             WHERE id = ?"
        );
        
        $stmt->execute([
            $data['name'] ?? $guest->name,
            $data['email'] ?? $guest->email,
            $data['phone'] ?? $guest->phone,
            $data['identity_number'] ?? $guest->identityNumber,
            $data['identity_type'] ?? $guest->identityType,
            $data['date_of_birth'] ?? $guest->dateOfBirth,
            $data['address'] ?? $guest->address,
            $data['city'] ?? $guest->city,
            $data['country'] ?? $guest->country,
            $data['notes'] ?? $guest->notes,
            $guest->id
        ]);

        return $this->findById($guest->id);
    }

    public function delete(Guest $guest): bool
    {
        $stmt = $this->db->prepare("DELETE FROM guests WHERE id = ?");
        return $stmt->execute([$guest->id]);
    }

    public function search(array $filters): array
    {
        $sql = "SELECT * FROM guests WHERE 1=1";
        $params = [];

        if (isset($filters['name'])) {
            $sql .= " AND name LIKE ?";
            $params[] = '%' . $filters['name'] . '%';
        }

        if (isset($filters['email'])) {
            $sql .= " AND email LIKE ?";
            $params[] = '%' . $filters['email'] . '%';
        }

        if (isset($filters['phone'])) {
            $sql .= " AND phone LIKE ?";
            $params[] = '%' . $filters['phone'] . '%';
        }

        if (isset($filters['identity_number'])) {
            $sql .= " AND identity_number LIKE ?";
            $params[] = '%' . $filters['identity_number'] . '%';
        }

        if (isset($filters['city'])) {
            $sql .= " AND city = ?";
            $params[] = $filters['city'];
        }

        if (isset($filters['country'])) {
            $sql .= " AND country = ?";
            $params[] = $filters['country'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map([$this, 'mapToGuest'], $data);
    }

    public function getWithReservations(int $id): ?Guest
    {
        // For now, return guest without reservations
        // Reservations relationship will be handled separately
        return $this->findById($id);
    }

    private function mapToGuest(array $data): Guest
    {
        return new Guest([
            'id' => (int) $data['id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'identityNumber' => $data['identity_number'],
            'identityType' => $data['identity_type'],
            'dateOfBirth' => $data['date_of_birth'],
            'address' => $data['address'],
            'city' => $data['city'],
            'country' => $data['country'],
            'notes' => $data['notes'],
            'createdAt' => $data['created_at'],
            'updatedAt' => $data['updated_at'],
        ]);
    }
}
