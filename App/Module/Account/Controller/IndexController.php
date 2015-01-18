<?php
class Account_Controller_IndexController extends Hana_Controller
{
	public function Index(){
		global $view;
		$view->setData('test','this data from Account_Controller_IndexController->Index method.');
		global $project;
		$model = $project->getModel('Default_Model_Default');
		$view->setData('tables',$model->test());
		
	}
}