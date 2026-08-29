<?php

namespace App\Core\Database;

use Illuminate\Database\DatabaseManager;

interface UnitOfWorkInterface
{
    public function beginTransaction(): void;
    public function commit(): void;
    public function rollback(): void;
    public function executeInTransaction(callable $callback): mixed;
    public function getConnection(): DatabaseManager;
}
