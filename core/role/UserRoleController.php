<?php
    namespace App\Core\Role;

    class UserRoleController extends \App\Core\Controller {
        public function __pre() {
            # Redirect users who have not logged in previously
            if($this->getSession()->get("user_id") === null) {
                $this->redirect(\Configuration::BASE . "administrator/login");
            }
        }
    }