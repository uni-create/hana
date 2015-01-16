<?php
class Account_Hook_Settings implements Hana_Hook
{
	public function beforeRoute(){
		$params = array(
			'redirects'=>array(
				'form'=>array(
					'success'=>'admin/',
					'error'=>'admin/login'
				),
				'logined'=>array(
					'error'=>'admin/login'
				)
			),
			'cookieName'=>'hana_admin'
		);
		$accountModule = Hana::getModule('Account');
		$loginController = $accountModule->getController('Account_Controller_LoginController');
		$loginController->setParams($params);
		
		
	}
}