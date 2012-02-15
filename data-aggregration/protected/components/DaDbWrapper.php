<?php
  class DaDbWrapper {
    public static function now(){
      return new CDbExpression("Now()");
    } 
  }