<?php
class Account_Hook_Secure implements Hana_Hook
{
	public function beforeRoute(){
		global $router;
		$accountModule = Hana::getModule('Account');
		$params = $router->getParams();
		if(!empty($params['attributes']['level'])){
			$loginController = $accountModule->getController('Account_Controller_LoginController');
			if(!$loginController->is_logined()){
				$loginController->login_error_redirect();
			}
		}elseif($params['attributes']['project'] == 'Admin'){
			$params['attributes']['frame'] = 'Login';
			$params['attributes']['outline'] = 'Login';
			$router->setParams($params);
		}
	}
}