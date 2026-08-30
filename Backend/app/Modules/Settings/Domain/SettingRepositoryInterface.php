<?php

namespace App\Modules\Settings\Domain;

interface SettingRepositoryInterface
{
    public function all(): array;
    public function findByKey(string $key): ?Setting;
    public function findByCategory(string $category): array;
    public function create(array $data): Setting;
    public function update(Setting $setting, array $data): Setting;
    public function delete(Setting $setting): bool;
    public function updateValue(string $key, string $value): bool;
}
