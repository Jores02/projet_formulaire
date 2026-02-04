<?php

require_once __DIR__ . '/db/Migration.php';

function migrate()
{
    $migrationFiles = glob(__DIR__ . '/migrations/*.php');
    
    $host = 'mysql';
    $dbname = 'tdR606';
    $user = 'root';
    $password = 'rootpassword';

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
        id VARCHAR(255) PRIMARY KEY,
        applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $stmt = $pdo->query("SELECT id FROM migrations");
    $applieds = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($migrationFiles as $file) {
        $migrationName = basename($file, '.php');
        $migrationId = explode('_', $migrationName)[1];

        if (in_array($migrationId, $applieds)) {
            echo "Skipping: $migrationId\n";
            continue;
        }

        require_once $file;
        $className = 'Migration\\' . $migrationName;

        if (class_exists($className)) {
            $migration = new $className($pdo);
            $migration->apply();

            $pdo->exec("INSERT INTO migrations (id) VALUES ('$migrationId')");

            echo "Applied: $migrationId\n";
        }
    }
}

migrate();
