<?php

 class A {
   
 }
 
 class B{
   
 }

 class C extends A {
   
 }

 class CodeTest extends CDbTestCase {
   public function testCode(){
      $a = new A();
      $b = new B();
      
      $c = new C();
      echo get_class($c);
      echo "\n";
      echo get_parent_class($c);
      
   }
 }