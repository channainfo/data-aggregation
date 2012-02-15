<?php
/**
 * Data Aggregation Controller
 * @author Sokha RUM
 */
class DaController extends Controller {
  public $layout = "//layouts/default" ;
  
	function beforeAction($action) {
		if(Yii::app()->user->isGuest) {
		    if ($action->controller->getId() == "user" && $action->id == "login") {
		        return true;
		    } else {
		        $this->redirect($this->createUrl("user/login"));
		        return false;
		    } 
		} else {
			if ($action->controller->getId() == "user" && $action->id == "login") {
				$this->redirect($this->createUrl("main/dashboard"));
		        return false;
			} else {
			    return true;
			}
		}
	}
}