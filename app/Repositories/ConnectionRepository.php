<?php

declare(strict_types=1);

namespace App\Repositories;

use PDOException;

final class ConnectionRepository extends AbstractRepository
{
    public function isAlive(): bool
    {
        try {
            $this->pdo()->query('SELECT 1');

            return true;
        } catch (PDOException) {
            return false;
        }
    }
}
