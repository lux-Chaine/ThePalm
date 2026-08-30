<?php

namespace App\Modules\Settings\Presentation;

use App\Core\Bus\CommandBus;
use App\Core\Bus\QueryBus;
use App\Core\Http\Request;
use App\Core\Http\ResponseFormatter;
use App\Modules\Settings\Application\Commands\UpdateSettingCommand;
use App\Modules\Settings\Application\Queries\GetAllSettingsQuery;
use App\Modules\Settings\Application\Queries\GetSettingByKeyQuery;

class SettingController
{
    public function __construct(
        protected CommandBus $commandBus,
        protected QueryBus $queryBus
    ) {}

    public function index(Request $request): array
    {
        $query = new GetAllSettingsQuery(
            category: $request->get('category')
        );

        $settings = $this->queryBus->dispatch($query);

        return ResponseFormatter::collection($settings);
    }

    public function show(string $key): array
    {
        $query = new GetSettingByKeyQuery($key);
        $setting = $this->queryBus->dispatch($query);

        if (!$setting) {
            return ResponseFormatter::notFound('Setting', $key);
        }

        return ResponseFormatter::item($setting);
    }

    public function update(Request $request, string $key): array
    {
        $command = new UpdateSettingCommand(
            key: $key,
            value: $request->get('value')
        );

        $result = $this->commandBus->dispatch($command);

        if ($result) {
            return ResponseFormatter::updated($result, 'Setting updated successfully');
        }

        return ResponseFormatter::error('Failed to update setting', 'update_failed', 500);
    }
}
