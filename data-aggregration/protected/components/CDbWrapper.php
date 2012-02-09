<?php
  class CDbWrapper {
    public static function now(){
      return new CDbExpression("Now()");
    } 
  }