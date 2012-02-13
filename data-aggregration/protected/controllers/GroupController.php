<?php
  class GroupController extends Controller{
    public $layout = "//layouts/default";
    
    public function actionCreate(){
      $model = new Group();
      if(isset($_POST["Group"])){
        $model->setAttributes($_POST["Group"]);
        if($model->save()){
          Yii::app()->user->setFlash("success", "Group has been created successfully") ;
          $this->redirect("index");
        }else
          Yii::app()->user->setFlash("error", "Failed to create group");
      }
      $this->render("create", array("model" => $model));
    }
    
    public function actionIndex(){
      $model = new Group();
      
      $criteria = new CDbCriteria();
      $criteria->order = "name DESC" ;

      $itemCount = $model->count($criteria);
      
      $pager = new CPagination($itemCount);
      $pager->pageSize = DaConfig::PAGE_SIZE;
      $pager->applyLimit($criteria);
      
      $rows = $model->findAll($criteria);    
      $this->render("index", array(
          "rows" => $rows,
          "pager" => $pager
      ));
      
      
      
      
      
      
    }
    
  }
?>
