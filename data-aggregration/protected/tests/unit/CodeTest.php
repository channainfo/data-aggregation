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
 function a(){
   echo "\na";
   return false;
 }
 function b(){
   echo "\nb";
   return true;
 }
 function c(){
   echo "\nc";
   return true;
 }
 function d(){
   echo "\nd";
   return true;
 }
 echo "\n-------------------"; 
 $a = a() && b() && c();
 echo "\n-------------------";
 $a = d() && b() && c();
 echo "\n-------------------";
 $a = d() && b() && c() && a();

 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 