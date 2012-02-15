<?php

class SiteConfigController extends DaController
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
  
  public function actionDelete($id){
    try{
      $model = $this->loadModel($id);
      $model->delete();
      Yii::app()->user->setFlash("success", "Site has been removed successfully");
    }
    catch(Exception $ex){
      Yii::app()->user->setFlash("error", "Failed to remove site");
    }
    $this->redirect($this->createUrl("siteconfig/index"));
  }

  public function actionUpdate($id){
    try{
      $model = SiteConfig::model()->findByPk($id);
      
      if(isset($_POST["SiteConfig"])){
        $model->setAttributes($_POST["SiteConfig"]);
        if($model->save()){
          Yii::app()->user->setFlash("success", "Site has been updated successfully");
          $this->redirect($this->createUrl("index"));
        }
        else
          Yii::app()->user->setFlash("error", "Failed to update site");
      }
      $this->render("update", array("model" => $model));
      
    }
    catch (Exception $ex){
      throw  new CHttpException("Invalid site configuration");
    }
    
    
  }
  
  public function actionTestConnection(){
    $siteconfig = $_GET["SiteConfig"];
    $db = new DaDbConnection($siteconfig["host"], $siteconfig["user"], $siteconfig["password"] , $siteconfig["db"] );
    
    if($db->isConnected())
      echo "true";
    else 
      echo "false" ;
    
  }
  public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}