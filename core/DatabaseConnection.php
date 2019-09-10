<?php
    namespace App\Core;
    use \PDO;

    class DatabaseConnection {
        private $connection;
        private $configuration;

        public function __construct(DatabaseConfiguration $databaseConfiguration){
            $this->configuration = $databaseConfiguration;
        }

        public function getConnection(): PDO {
            if($this->connection == NULL){
                $this->connection = new PDO($this->configuration->getSourceString(), 
                                            $this->configuration->getUser(),
                                            $this->configuration->getPwd());
            }
            return $this->connection;
        }

        
    }