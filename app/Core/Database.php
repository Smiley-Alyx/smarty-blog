<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    private ?PDO $connection = null;

    /**
     * @param array{host: string, port: int, database: string, username: string, password: string, charset: string} $config
     */
    public function __construct(
        private readonly array $config,
    ) {
    }

    public function connection(): PDO
    {
        if ($this->connection === null) {
            $this->connection = $this->connect();
        }

        return $this->connection;
    }

    private function connect(): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $this->config['host'],
            $this->config['port'],
            $this->config['database'],
            $this->config['charset'],
        );

        try {
            return new PDO(
                $dsn,
                $this->config['username'],
                $this->config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ],
            );
        } catch (PDOException $exception) {
            throw new RuntimeException('Database connection failed.', 0, $exception);
        }
    }
}
