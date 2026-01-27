<?php

namespace Migration {

    use PDO;

    class Migration
    {
        protected $pdo;
        protected $scripts = [];

        public function __construct(PDO $pdo, array $scripts)
        {
            $this->pdo = $pdo;
            $this->scripts = $scripts;
        }

        public function apply()
        {
            foreach ($this->scripts as $script) {
                $this->pdo->exec($script);
            }
        }
    }
}
