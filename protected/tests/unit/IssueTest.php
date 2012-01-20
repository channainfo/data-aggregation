<?php
  class IssueTest extends CDbTestCase {
     public $fixtures=array(
        'projects'=>'Project',
        'issues'=>'Issue',
     );
 
    
     public function testGetTypes(){
         $options = Issue::model()->getTypeOptions();
         $this->assertTrue(is_array($options));
         $this->assertTrue( 3 == count($options));
         $this->assertTrue(in_array("Feature", $options));
         $this->assertTrue(in_array("Task", $options));
         $this->assertTrue(in_array("Bug", $options));
     } 
     
     public function testGetStatusText(){
        $this->assertTrue('Started' == $this->issues('issueBug')->getStatusText());
     }
    
     public function testGetTypeText(){
        $this->assertTrue('Bug' == $this->issues('issueBug')->getTypeText());
     }

     
      
      
      
  }