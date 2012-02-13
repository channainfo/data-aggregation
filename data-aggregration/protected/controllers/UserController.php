<?php

class UserController extends DaController
{
	public $layout='//layouts/default';
	
	public function actionView($id) {
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
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
			$model->attributes = $_POST['User'];
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
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['User'])){
			$model->attributes = $_POST['User'];
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
        
        Yii::app()->user->setFlash("success", "Password has been changed");
        if($user->save())
          $this->redirect("index");
      }
    }
    $this->render("change", array("model" => $model));
  }

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(1 || Yii::app()->request->isPostRequest)
		{
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
    $criteria->order = 'modified_at DESC, login ASC';
    $criteria->with = array("group") ;
    
    $itemCount = $model->count($criteria);
    $pages = new CPagination($itemCount);
    $pages->pageSize = 10 ;
    $pages->applyLimit($criteria);
    
    $users = $model->findAll($criteria);
		$this->render('index',array(
			'users'=>$users,
      'pages' => $pages  
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
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

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
  
}
