<?php

namespace App\Modules\Identity\Infrastructure;

use App\Modules\Identity\Domain\User;
use App\Modules\Identity\Domain\UserRepositoryInterface;
use PDO;

class EloquentUserRepository implements UserRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;port=3306;dbname=palm_hotel;charset=utf8mb4', 'root', '');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ? AND deleted_at IS NULL LIMIT 1");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? new User($data) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? AND deleted_at IS NULL LIMIT 1");
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? new User($data) : null;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM users WHERE deleted_at IS NULL");
        $users = [];
        
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new User($data);
        }
        
        return $users;
    }

    public function create(array $data): User
    {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role, user_type, email_verified_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['password'],
            $data['role'],
            $data['user_type'] ?? 'staff',
            $data['email_verified_at'] ?? null
        ]);
        
        return $this->findById((int) $this->db->lastInsertId());
    }

    public function update(User $user, array $data): User
    {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            if ($key !== 'id' && $value !== null) {
                $fields[] = "$key = ?";
                $values[] = $value;
            }
        }
        
        if (!empty($fields)) {
            $fields[] = "updated_at = NOW()";
            $values[] = $user->id;
            
            $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($values);
        }
        
        return $this->findById($user->id);
    }

    public function delete(User $user): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET deleted_at = NOW() WHERE id = ?");
        return $stmt->execute([$user->id]);
    }

    public function findByRole(string $role): array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE role = ? AND deleted_at IS NULL");
        $stmt->execute([$role]);
        $users = [];
        
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new User($data);
        }
        
        return $users;
    }

    public function countByRole(string $role): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE role = ? AND deleted_at IS NULL");
        $stmt->execute([$role]);
        return (int) $stmt->fetchColumn();
    }
}
