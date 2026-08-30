<?php

namespace App\Modules\Settings\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Core\Bus\QueryInterface;
use App\Modules\Settings\Domain\SettingRepositoryInterface;
use App\Modules\Settings\Infrastructure\EloquentSettingRepository;

class GetAllSettingsQueryHandler implements QueryHandlerInterface
{
    public function __construct()
    {
        $this->settingRepository = new EloquentSettingRepository();
    }

    public function handle(QueryInterface $query): array
    {
        if ($query->category) {
            $settings = $this->settingRepository->findByCategory($query->category);
        } else {
            $settings = $this->settingRepository->all();
        }

        return array_map(fn($setting) => $setting->toArray(), $settings);
    }
}
