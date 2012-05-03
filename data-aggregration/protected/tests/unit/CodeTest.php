<?php

  class A {
    public function success(){
      echo "success";
    }
    public function failed(){
      
    }
  }
  
  $a = new A();


  class CodeTest extends CTestCase{
    public function testMethodExist(){
      $a  = new A();
      $this->assertEquals(method_exists($a, "success"), true);
      $this->assertEquals(method_exists($a, "failed"), true);
    }
    
    public function testMethodNotExist(){
      $a  = new A();
      $this->assertEquals(method_exists($a, "not"), false);
      $this->assertEquals(method_exists($a, "not exist"), false);
    }
  }
  
  $data = array("a"=>1,"b"=>2, "c" => 3);
  
  print_r(array_keys($data));
  
?>