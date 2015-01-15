<?php
class Account_Hook_Settings implements Hana_Hook
{
	public function beforeRoute(){
		global $project;
		$loginController = $project->getModule('Account')->getController('Account_Controller_LoginController');
		$loginController->setRedirectUrls(array(
			'form'=>array(
				'success'=>'admin/',
				'error'=>'admin/login'
			),
			'logined'=>array(
				'error'=>'admin/login'
			)
		));
		
		$loginModel = $project->getModule('Account')->getModel('Account_Model_Login');
		$loginModel->setCookieName('hana_admin');
	}
}