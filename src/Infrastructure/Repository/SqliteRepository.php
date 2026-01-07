<?php

namespace Aucoffre\Infrastructure\Repository;

use PDO;

/**
 * Classe abstraite pour les repositories SQLite
 * @copyright ©2025 AuCOFFRE.com
 */
abstract readonly class SqliteRepository
{
    /**
     * @param PDO $pdo
     */
    public function __construct(
        protected PDO $pdo
    ) {
    }
}
