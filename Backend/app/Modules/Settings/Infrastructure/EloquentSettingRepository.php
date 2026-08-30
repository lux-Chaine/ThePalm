<?php

namespace App\Modules\Settings\Infrastructure;

use App\Modules\Settings\Domain\Setting;
use App\Modules\Settings\Domain\SettingRepositoryInterface;
use PDO;

class EloquentSettingRepository implements SettingRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;port=3306;dbname=palm_hotel;charset=utf8mb4', 'root', '');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM settings");
        $settings = [];
        
        while ($record = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[] = Setting::fromArray($record);
        }
        
        return $settings;
    }

    public function findByKey(string $key): ?Setting
    {
        $stmt = $this->db->prepare("SELECT * FROM settings WHERE `key` = ? LIMIT 1");
        $stmt->execute([$key]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $record ? Setting::fromArray($record) : null;
    }

    public function findByCategory(string $category): array
    {
        $stmt = $this->db->prepare("SELECT * FROM settings WHERE category = ?");
        $stmt->execute([$category]);
        $settings = [];
        
        while ($record = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[] = Setting::fromArray($record);
        }
        
        return $settings;
    }

    public function create(array $data): Setting
    {
        $stmt = $this->db->prepare("INSERT INTO settings (`key`, value, type, category, description, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
            $data['key'],
            $data['value'],
            $data['type'] ?? 'string',
            $data['category'] ?? 'general',
            $data['description'] ?? null
        ]);

        $data['id'] = (int) $this->db->lastInsertId();
        return Setting::fromArray($data);
    }

    public function update(Setting $setting, array $data): Setting
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
            $values[] = $setting->id;
            
            $sql = "UPDATE settings SET " . implode(', ', $fields) . " WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($values);
        }

        return $this->findByKey($setting->key);
    }

    public function delete(Setting $setting): bool
    {
        $stmt = $this->db->prepare("DELETE FROM settings WHERE id = ?");
        return $stmt->execute([$setting->id]);
    }

    public function save(Setting $setting): bool
    {
        $stmt = $this->db->prepare("UPDATE settings SET value = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$setting->value, $setting->id]);
    }

    public function updateValue(string $key, string $value): bool
    {
        $stmt = $this->db->prepare("UPDATE settings SET value = ?, updated_at = NOW() WHERE `key` = ?");
        return $stmt->execute([$value, $key]);
    }
}
