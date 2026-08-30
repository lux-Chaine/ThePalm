<?php

namespace App\Modules\Settings\Application\Queries;

use App\Core\Bus\QueryHandlerInterface;
use App\Core\Bus\QueryInterface;
use App\Modules\Settings\Domain\SettingRepositoryInterface;
use App\Modules\Settings\Infrastructure\EloquentSettingRepository;

class GetSettingByKeyQueryHandler implements QueryHandlerInterface
{
    protected $settingRepository;

    public function __construct()
    {
        $this->settingRepository = new EloquentSettingRepository();
    }

    public function handle(QueryInterface $query): ?array
    {
        $setting = $this->settingRepository->findByKey($query->key);
        
        return $setting ? $setting->toArray() : null;
    }
}
