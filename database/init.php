<?php

/**
 * Script d'initialisation de la base de données SQLite
 * Usage: php database/init.php
 * @copyright ©2025 AuCOFFRE.com
 */

declare(strict_types=1);

use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = require dirname(__DIR__) . '/bootstrap.php';

//Chemins
$databasePath = $container->get('db.path');
$databaseTestPath = $container->get('db_test.path');

$schemaPath = __DIR__ . '/schema.sql';
$seedPath = __DIR__ . '/seed.sql';

echo "========================================\n";
echo "  Initialisation de la base de données\n";
echo "========================================\n\n";

//Vérification existence
if (file_exists($databasePath)) {
    echo "La base de données existe déjà : $databasePath\n";
    echo "Voulez-vous la recréer ? (o/N) : ";

    $handle = fopen("php://stdin", "r");
    $line = trim(fgets($handle));
    fclose($handle);

    if (strtolower($line) !== 'o') {
        echo "\nOpération annulée.\n";
        exit(0);
    }

    unlink($databasePath);
    echo "Ancienne base supprimée.\n\n";
}


try {
    /** @var PDO $pdo */
    $pdo = $container->get(PDO::class);
    echo "Création de la base de données...\n";

    $pdo->beginTransaction();
    //Exécution du schéma
    echo "Création du schéma...\n";
    if (!file_exists($schemaPath)) {
        throw new RuntimeException("Fichier schema.sql introuvable : $schemaPath");
    }

    $schema = file_get_contents($schemaPath);
    $pdo->exec($schema);
    echo "Schéma créé avec succès\n\n";

    //Insertion des données de test
    echo "Insertion des données de test...\n";
    if (!file_exists($seedPath)) {
        throw new RuntimeException("Fichier seed.sql introuvable : $seedPath");
    }

    $seed = file_get_contents($seedPath);
    $pdo->exec($seed);
    echo "Données insérées avec succès\n\n";
    $pdo->commit();

    //Vérification
    $stmt = $pdo->query("SELECT COUNT(*) as 'nb_account' FROM account");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $result['nb_account'] ?? 0;

    echo "Nombre de comptes créés : $count\n";

} catch (Throwable $e) {
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "\nErreur inattendue : " . $e->getMessage() . "\n";
    exit(1);
}

//Initialisation de la base de test

if (file_exists($databaseTestPath)) {
    unlink($databaseTestPath);
}

try {
    /** @var PDO $pdo */
    $pdo = $container->get('pdo_test');
    echo "Création de la base de données de test...\n";

    $pdo->beginTransaction();
    //Exécution du schéma
    echo "Création du schéma...\n";
    if (!file_exists($schemaPath)) {
        throw new RuntimeException("Fichier schema.sql introuvable : $schemaPath");
    }

    $schema = file_get_contents($schemaPath);
    $pdo->exec($schema);
    echo "Schéma créé avec succès\n\n";

    $pdo->commit();

    echo "========================================\n";
    echo "   Initialisation terminée avec succès !\n";
    echo "========================================\n";

} catch (Throwable $e) {
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "\nErreur inattendue : " . $e->getMessage() . "\n";
    exit(1);
}
