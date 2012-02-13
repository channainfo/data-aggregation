<?php
class MainController extends DaController {
	public function actionDashboard(){
		$this->pageTitle = "Dashboard";
		$this->layout = "//layouts/default" ;
		$this->render("dashboard");
	}
}