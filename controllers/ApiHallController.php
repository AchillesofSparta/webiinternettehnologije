<?php
    namespace App\Controllers;

    class ApiHallController extends \App\Core\ApiController {

        public function show($id){
            $hallModel = new \App\Models\HallModel($this->getDatabaseConnection());
            $halls = $hallModel->getById($id);
            $this->set("hall", $halls);
        }
       

    }