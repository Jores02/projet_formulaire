<?php

namespace Migration;

use PDO;

require_once __DIR__ . '/../db/Migration.php';

class migration_base142601272026 extends Migration
{
    public function __construct(PDO $pdo)
    {
        $scripts = [
            "CREATE TABLE tirages (id INT AUTO_INCREMENT PRIMARY KEY, valeur_tiree VARCHAR(255), date_tirage TIMESTAMP DEFAULT CURRENT_TIMESTAMP)"
        ];
        parent::__construct($pdo, $scripts);
    }
}
