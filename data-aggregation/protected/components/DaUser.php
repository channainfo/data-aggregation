<?php
 class DaUser extends CWebUser {
   /**
     * Overrides a Yii method that is used for roles in controllers (accessRules).
     *
     * @param string $operation Name of the operation required (here, a role).
     * @param mixed $params (opt) Parameters for this operation, usually the object to access.
     * @return bool Permission granted?
     */
    public function checkAccess($operation, $params=array())
    {   
        echo "check access \n" ;
        print_r($operation);
        print_r($params);
        echo "\n======================\n";
        
        if (empty($this->id)) {
            // Not identified => no rights
            return false;
        }
        $role = $this->getState("group_id");
        if (User::isUserAdmin($role)) {
            return true; // admin role has access to everything
        }
        // allow access if the operation request is the current user's role
        //return ($operation === $role);
    }
    
    public function isAdmin(){
      $groupId = $this->getState("groupId");
      return User::isUserAdmin($groupId);
    }
 }
