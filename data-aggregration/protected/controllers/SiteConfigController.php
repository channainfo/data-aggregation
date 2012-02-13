<?php

class SiteConfigController extends Controller
{
  public $layout = "//layouts/default" ;
	public function actionCreate(){
   
    $model = new SiteConfig();
    $this->render("create", array("model" => $model));
  }
}