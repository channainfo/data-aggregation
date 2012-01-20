<?php
  class ProjectTest extends CDbTestCase{
     
     public $fixtures=array(
        'projects' => 'Project',
        'users' => 'User',
        'projUsrAssign' => ':tbl_project_user_assignment',
     );
 
      
     public function testCRUD(){
          
          
        $newProject = new Project();
        $newProjectName  = "Ruby hack";
        $newProject->setAttributes(array(
           "name" => $newProjectName,
           "description" => "{$newProjectName}- Description",
           // "create_user_id" => 1,
           // "update_user_id" => 1        
        ));
        
        //set the application user id to the first user in our users fixture data
        $userId = $this->users('user1')->id ;   
        Yii::app()->user->setId($userId);

        //save the new project, triggering attribute validation
        $this->assertTrue($newProject->save());
   

           
           
        $return = $newProject->save(false); 
        echo Yii::ch_debug($return) ;
        $this->assertTrue($return);   
          
        //READ back the newly created project
        $retrievedProject=Project::model()->findByPk($newProject->id);
        $this->assertTrue($retrievedProject instanceof Project);
        $this->assertEquals($newProjectName,$retrievedProject->name);
        

        
        //UPDATE the newly created project
        $updatedProjectName = 'Updated Test Project 1';
        $newProject->name = $updatedProjectName; 
        $this->assertTrue($newProject->save(false));
        
        
        
        //DELETE the project
        $newProjectId = $newProject->id;
        $this->assertTrue($newProject->delete());
        $deletedProject = Project::model()->findByPk($newProjectId);
        $this->assertEquals(NULL,$deletedProject);

        $this->assertEquals(Yii::app()->user->id, $retrievedProject->create_user_id);

        
        
      }
      
     public function testGetUserOptions(){
         
     }
  }
?>
