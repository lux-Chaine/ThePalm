<?php

namespace App\Core\Bus;

use Illuminate\Support\Facades\App;
use Exception;

class QueryBus
{
    public function dispatch(QueryInterface $query): mixed
    {
        $handlerClass = $this->getHandlerClass($query);
        
        if (!class_exists($handlerClass)) {
            throw new Exception("Query handler not found: {$handlerClass}");
        }

        $handler = App::make($handlerClass);
        
        if (!$handler instanceof QueryHandlerInterface) {
            throw new Exception("Handler must implement QueryHandlerInterface");
        }

        return $handler->handle($query);
    }

    protected function getHandlerClass(QueryInterface $query): string
    {
        $queryClass = get_class($query);
        return str_replace('Query', 'QueryHandler', $queryClass) . 'Handler';
    }
}
