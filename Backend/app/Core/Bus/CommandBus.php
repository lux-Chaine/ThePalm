<?php

namespace App\Core\Bus;

use Illuminate\Support\Facades\App;
use Exception;

class CommandBus
{
    public function dispatch(CommandInterface $command): mixed
    {
        $handlerClass = $this->getHandlerClass($command);
        
        if (!class_exists($handlerClass)) {
            throw new Exception("Command handler not found: {$handlerClass}");
        }

        $handler = App::make($handlerClass);
        
        if (!$handler instanceof CommandHandlerInterface) {
            throw new Exception("Handler must implement CommandHandlerInterface");
        }

        return $handler->handle($command);
    }

    protected function getHandlerClass(CommandInterface $command): string
    {
        $commandClass = get_class($command);
        return str_replace('Command', 'CommandHandler', $commandClass) . 'Handler';
    }
}
