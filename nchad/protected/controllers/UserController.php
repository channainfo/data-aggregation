<?php

class UserController extends Controller
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
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('User', array(
        "pagination" => array("pageSize" => 4),
        "sort" => array(
            "defaultOrder" => array("id")
        )
      )
    );
    
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
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
