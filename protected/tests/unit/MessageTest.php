<?php
Yii::import("application.controllers.MessageController");
class MessageTest extends CTestCase{
   
    public function testRepeat(){
        $message = new MessageController("messageTest");
        $y = "This is a message" ;
        $returnMsg = $message->repeat($y);
        $this->assertEquals($returnMsg, $y);
        
        
    }
    
    
}



