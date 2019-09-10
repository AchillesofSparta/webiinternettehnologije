<?php
    namespace App\Core;

    class DatabaseConfiguration {
        private $host;
        private $user;
        private $pwd;
        private $name;

        # Konstruktor
        public function __construct(string $h, string $u, string $p, string $n){
            $this->host=$h;
            $this->user=$u;
            $this->pwd=$p;
            $this->name=$n;
        }

        # Geteri
        public function getSourceString(): string {
            return "mysql:host={$this->host};dbname={$this->name};charset=utf8";
        }

        public function getUser(): string {
            return $this->user;
        }

        public function getPwd(): string {
            return $this->pwd;
        }

    }