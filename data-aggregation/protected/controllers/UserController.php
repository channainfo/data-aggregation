<?php

class UserController extends DaController{
  public function accessRules(){
      return array(
          array('allow',
                'actions'=>array('login'),
                'users'=>array('*')),
          array('allow',  // allow all users to perform 'list' and 'show' actions
                'actions'=>array('index','view','logout','change'),
                'users'=>array('@') ),
          
          array('allow', // allow authenticated user to perform 'update' and 'delete' actions
                'actions'=>array('create', 'delete', 'update', 'reset'),
                'users'=>array('@'),
                'expression'=> '$user->isAdmin()',//$isOwnerOrAdmin,
          ),
          
          array('deny', 
                'users'=>array('*')),
      );
   }
  
  public function actionLogin(){
    
    if(!Yii::app()->user->isGuest){
      $this->redirect(Yii::app()->user->returnUrl);
    }
    $model = new LoginForm();
    if(isset($_POST["LoginForm"])){
      $model->setAttributes($_POST["LoginForm"]);
      if($model->validate() && $model->login()){
        $this->redirect(Yii::app()->user->returnUrl);
      }
    }
    $this->render( "login", array( "model" => $model) );
  }
  
  public function actionLogout(){
    Yii::app()->user->logout();
		$this->redirect("login");
  }

	public function actionCreate()
	{
		$model=new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User'])){
			$model->setAttributes($_POST['User']);
			if($model->save()){
        Yii::app()->user->setFlash("success", "User has been created");
				$this->redirect(array('index'));
      }
      else{
        Yii::app()->user->setFlash("error", "Failed to create user");
      }
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id){
		$model=$this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['User'])){
			$model->setAttributes($_POST['User']);
			if($model->save()){
        Yii::app()->user->setFlash("success","User has been updated successfully");
				$this->redirect(array('index'));
      }else
        Yii::app()->user->setFlash("error", "Failed to update user");
		}else{
      $model->password = "";
    }
		$this->render('update',array(
			'model'=>$model,
		));
	}
  
  public function actionReset(){
    $model = new ResetForm();
    $model->user_id = $_GET["id"];
    if(isset($_POST["ResetForm"])){
      $attributes = $_POST["ResetForm"];
      
      $model->setAttributes($attributes);
      
      if($model->validate()){
        $user = User::model()->findByPk($_POST["ResetForm"]["user_id"]);
        $user->setAttribute("password", $_POST["ResetForm"]["password"] );
        
        if($user->save()){
          Yii::app()->user->setFlash("success", "Password has been reset");
          $this->redirect($this->createUrl("user/index"));
        }
      }
    }
    $this->render("reset", array("model" => $model));
  }
  
  public function actionChange(){
    $model = new PasswordChangeForm();
    if(isset($_POST["PasswordChangeForm"])){
      $attributes = $_POST["PasswordChangeForm"];
      $currentUserId =  Yii::app()->user->id ;
      $attributes["user_id"] = $currentUserId;
      $model->setAttributes($attributes);
      
      if($model->validate()){
        $user = User::model()->findByPk($currentUserId);
        $user->password = $_POST["PasswordChangeForm"]["password"];
        $user->password_repeat = $_POST["PasswordChangeForm"]["password"];
        if($user->save()){
          Yii::app()->user->setFlash("success", "Password has been changed");
          $this->redirect("index");
        }
      }
    }
    $this->render("change", array("model" => $model));
  }

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id){
		if(1 || Yii::app()->request->isPostRequest){
			// we only allow deletion via POST request
			try{
        $this->loadModel($id)->delete();
        Yii::app()->user->setFlash("success", "User has been deleted");
      }
      catch(Exception $ex){
        Yii::app()->user->setFlash("error", "Failed to delete user");
      }
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
    $model = new User();
    $criteria = new CDbCriteria();
    $criteria->order = 't.id DESC, login ASC';
    $criteria->with = array("group") ;
    
    $itemCount = $model->count($criteria);
    $pages = new CPagination($itemCount);
    $pages->pageSize = DaConfig::PAGE_SIZE ;
    $pages->applyLimit($criteria);
    
    $users = $model->findAll($criteria);
		$this->render('index',array(
			'users'=>$users,
      'pages' => $pages  
		));
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
