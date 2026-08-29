<?php

namespace App\Core\Database;

use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\DB;
use Exception;

class UnitOfWork implements UnitOfWorkInterface
{
    protected DatabaseManager $db;
    protected int $transactionLevel = 0;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    public function beginTransaction(): void
    {
        if ($this->transactionLevel === 0) {
            $this->db->beginTransaction();
        }
        $this->transactionLevel++;
    }

    public function commit(): void
    {
        if ($this->transactionLevel === 1) {
            $this->db->commit();
        }
        $this->transactionLevel = max(0, $this->transactionLevel - 1);
    }

    public function rollback(): void
    {
        if ($this->transactionLevel === 1) {
            $this->db->rollBack();
        }
        $this->transactionLevel = max(0, $this->transactionLevel - 1);
    }

    public function executeInTransaction(callable $callback): mixed
    {
        $this->beginTransaction();
        try {
            $result = $callback();
            $this->commit();
            return $result;
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function getConnection(): DatabaseManager
    {
        return $this->db;
    }
}
