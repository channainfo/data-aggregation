<?php

class SiteConfigController extends Controller
{
  public $layout = "//layouts/default" ;
	public function actionCreate(){
   
    $model = new SiteConfig();
    if(isset($_POST["SiteConfig"])){
      $model->setAttributes($_POST["SiteConfig"]);
      if($model->save()){
        Yii::app()->user->setFlash("success", "Site configuration has been saved successfully");
        $this->redirect($this->createUrl("index"));
      }else
        Yii::app()->user->setFlash("error", "Failed to save site configuration"); 
      
      
    }
    $this->render("create", array("model" => $model));
  }
  
  public function actionIndex(){
    $model = new SiteConfig();
    $criteria = new CDbCriteria();
    $criteria->order = " modified_at DESC " ;
    $itemCount = $model->count($criteria);
    $pages = new CPagination($itemCount);
    $pages->pageSize = DaConfig::PAGE_SIZE ;
    $pages->applyLimit($criteria);
    $sites = $model->findAll($criteria);
    $this->render("index", array("sites" => $sites, "pages" => $pages ));
  }
  
  public function actionTestConnection(){
//    DaTool::debug($_GET,1,0);
    
    $siteconfig = $_GET["SiteConfig"];
    $db = new DatabaseConnection($siteconfig["host"], $siteconfig["user"], $siteconfig["password"] , $siteconfig["db"] );
    
    if($db->isConnected())
      echo "true";
    else 
      echo "false" ;
    
  }
}