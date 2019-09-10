<?php
    namespace App\Controllers;

    use App\Core\DatabaseConnection;
    use App\Models\AdministratorModel;
    use App\Models\HallModel;
    use App\Models\FeatureModel;
    use App\Models\AdministratorLoginModel;
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

            # popunjavanje zbog zapisivanja koda
            $administratorLoginModel = new AdministratorLoginModel($this->getDatabaseConnection());
            
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

        


        public function delete($id) {
            die("TO DO YET");   
        }

    }