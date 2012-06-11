<?php
  class GroupController extends Controller{
    public function accessRules(){
      return array(
          array('allow',  // allow all users to perform 'list' and 'show' actions
                'actions'=>array('index'),
                'users'=>array('@') ),
          
          array('allow', // allow authenticated user to perform 'update' and 'delete' actions
                'actions'=>array('create'),
                'users'=>array('@'),
                'expression'=> '$user->isAdmin()',//$isOwnerOrAdmin,
          ),
          
          array('deny', 
                'users'=>array('*')),
      );
    }
    
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
