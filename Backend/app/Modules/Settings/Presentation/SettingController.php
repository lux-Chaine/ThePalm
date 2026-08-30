<?php

namespace App\Modules\Settings\Presentation;

use App\Core\Bus\CommandBus;
use App\Core\Bus\QueryBus;
use App\Core\Http\Request;
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

        return [
            'success' => true,
            'data' => $settings
        ];
    }

    public function show(string $key): array
    {
        $query = new GetSettingByKeyQuery($key);
        $setting = $this->queryBus->dispatch($query);

        if (!$setting) {
            return [
                'success' => false,
                'error' => 'Setting not found'
            ];
        }

        return [
            'success' => true,
            'data' => $setting
        ];
    }

    public function update(Request $request, string $key): array
    {
        $command = new UpdateSettingCommand(
            key: $key,
            value: $request->get('value')
        );

        $result = $this->commandBus->dispatch($command);

        return [
            'success' => $result,
            'message' => $result ? 'Setting updated successfully' : 'Failed to update setting'
        ];
    }
}
