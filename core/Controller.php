<?php
    namespace App\Core;

    class Controller {
        private $dbc;
        private $session;
        private $data = [];

        # Role controller, not to be used as part of Routes
        public function __pre() {
            
        }
        
        final function __construct(DatabaseConnection &$dbc) {
            $this->dbc = $dbc;
        }

        final public function &getSession(): \App\Core\Session\Session {
            return $this->session;
        }

        final public function setSession(\App\Core\Session\Session &$session) {
            $this->session = $session;
        }

        final public function &getDatabaseConnection(): \App\Core\DatabaseConnection {
            return $this->dbc;
        }

        final protected function set(string $name, $value): bool {
            $result = false;
            # Dozvoli normalnu konvenciju: Beskonacan broj reci
            if(preg_match("/^(?:[a-zA-Z0-9\s]+)*$/", $name)) {
                $this->data[$name] = $value;
                $result = true;
            }
            return $result;
        }

        final public function getData(): array {
            return $this->data;
        }

        # Umesto 307 stavljen je 303 response kako bi se iskoristio GET redirect umesto POST (koji nemam pa se koristi fallback ruta)
        final protected function redirect(string $path, int $code = 303) {
            ob_clean();
            header("Location: " . $path, true, $code);
            exit;
        }
    }