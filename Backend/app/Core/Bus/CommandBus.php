<?php

namespace App\Core\Bus;

use Exception;

class CommandBus
{
    public function dispatch(CommandInterface $command): mixed
    {
        $handlerClass = $this->getHandlerClass($command);
        
        if (!class_exists($handlerClass)) {
            throw new Exception("Command handler not found: {$handlerClass}");
        }

        // Instantiate handler directly without Laravel's container
        $handler = new $handlerClass();
        
        if (!$handler instanceof CommandHandlerInterface) {
            throw new Exception("Handler must implement CommandHandlerInterface");
        }

        return $handler->handle($command);
    }

    protected function getHandlerClass(CommandInterface $command): string
    {
        $commandClass = get_class($command);
        // Extract namespace and class name
        $lastSlash = strrpos($commandClass, '\\');
        $namespace = substr($commandClass, 0, $lastSlash);
        $className = substr($commandClass, $lastSlash + 1);
        
        // Replace 'Command' with 'CommandHandler' in class name only
        $handlerClassName = str_replace('Command', 'CommandHandler', $className);
        
        return $namespace . '\\' . $handlerClassName;
    }
}
