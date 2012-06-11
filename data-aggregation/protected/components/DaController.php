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
  public function filters(){
      return array('accessControl' );
  }
  public function download($fullName){
     $basename = str_replace(" ","_" , basename($fullName) );
     header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // some day in the past
     header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
     header("Content-type: application/octet-stream");
     header("Content-Disposition: attachment; filename={$basename}");
     header("Content-Transfer-Encoding: binary");
     //readfile($fullName);
     $handle = fopen($fullName, "rb");
     while (!feof($handle)) {
       echo fread($handle, 8192);
       ob_flush();
     }
     fclose($handle); 
   }
}