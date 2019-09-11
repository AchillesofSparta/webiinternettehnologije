<?php
    namespace App\Controllers;

    use App\Core\DatabaseConnection;
    use App\Models\AdministratorModel;
    use App\Models\HallModel;
    use App\Models\FeatureModel;
    use App\Models\AdministratorLoginModel;
    use App\Models\EventModel;
    use App\Core\Model;

    class HallController extends \App\Core\Controller {
        
        public function show($id) {
            // Get Hall data to display
            $hallModel = new HallModel($this->getDatabaseConnection());
            $halls = $hallModel->getById($id);
            $this->set("hall", $halls);  
            
            $hallFeatureModel = new FeatureModel($this->getDatabaseConnection());
            $hallFeatures = $hallFeatureModel->getAllByHallId($id);
            #print_r($hallFeatures[0]->value);
            if(!$hallFeatures){
                header("Location: /MB/");
                exit;
            }
            // Ovo je featureID, koristan za dodatan atribute feature-a
            # Prikaz info o Sali
            $this->set("hallFeatures", $hallFeatures);
            
            // Get Feature data to display
            #print_r($hallFeatures[0]);

            $hallFeatureNames = [];
            foreach ($hallFeatures as $hallFeature){
                $featureId = $hallFeature->feature_id;
                array_push($hallFeatureNames, $hallFeatureModel->getFeatureNameById($featureId));
            
                if(!$hallFeatureNames){
                    print_r("NEMA IMENA");
                    exit;
                }

            }
            
            # Prikaz imena Sale
            $this->set("hallFeatureNames", $hallFeatureNames);
            #print_r($hallFeatureNames[0]);

            $mixedArray = array_map(null, $hallFeatures, $hallFeatureNames);
            # Prikaz feature-a Sale
            $this->set("features", $mixedArray);
            #print_r($mixedArray[0][0]);

            /*
            // Check Hall availability - OLD
            if (isset($_POST['submit'])) {
                echo ("IN THERE");
                $checkDate = filter_input(INPUT_GET, "date", FILTER_SANITIZE_STRING);
                $eventModel = new EventModel($this->getDatabaseConnection());

                $resultCheck = false;
                if($eventModel->getByDate($checkDate)){
                    $resultCheck = true;
                }

                if($resultCheck){
                    $this->set("message","Hall unavailable for the selected date...");
                }

                $this->set("message","HALL AVAILABLE! Please call us to reserve the hall!");

            }
            */
            
            // OLD CODE
            # popunjavanje zbog zapisivanja koda
            //$administratorLoginModel = new AdministratorLoginModel($this->getDatabaseConnection());
            
            /* UPIS I MENJANJE U BAZI
            $ipAddress = filter_input(INPUT_SERVER, "REMOTE_ADDR");
            $administratorLoginModel->add(
                [
                    'ip_address' => $ipAddress,
                    "administrator_id" => "4"
                ], "administrator_login"
            );

            $administratorLoginModel->editById(21, [
                "administrator_id" => "5"
            ], "administrator_login");
            */
        }

        private function normaliseKeywords(string $keywords): string {
            $keywords = trim($keywords);
            $keywords = preg_replace("/ +/"," ", $keywords);
            return $keywords;
        }

        
        public function postSearch() {
            $hallModel = new HallModel($this->getDatabaseConnection());

            $query = filter_input(INPUT_POST, "query", FILTER_SANITIZE_STRING);
            $keywords = $this->normaliseKeywords($query);

            $halls = $hallModel->getAllBySearch($keywords);

            $this->set("halls", $halls);
        }

        public function delete($id) {
            die("TO DO YET");   
        }

        /*
        public function displayAvailability(string $mgs){
            $this->set("message", $mgs);
            $this->redirect(\Configuration::BASE . "halls/availability/display");
        }
        */

        public function displayAvailability() {}
        public function displayUnavailability() {}

        public function getAvailability() {
            $hallModel = new HallModel($this->getDatabaseConnection());
            $halls = $hallModel->getAll();
            $this->set("halls", $halls);
        }

        public function postAvailability() {
            $hallId = filter_input(INPUT_POST, "hall_name", FILTER_SANITIZE_STRING);
            $checkDate = filter_input(INPUT_POST, "check_date", FILTER_SANITIZE_STRING);

            $eventModel = new EventModel($this->getDatabaseConnection());

            if($eventModel->getByDateAndHallId($checkDate, $hallId)){
                $this->redirect(\Configuration::BASE . "halls/availability/negative");
                return;
            }
            
            $this->redirect(\Configuration::BASE . "halls/availability/positive");
        }

        

    }