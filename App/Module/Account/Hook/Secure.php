<?php
class Account_Hook_Secure implements Hana_Hook
{
	public function beforeRoute(){
		global $router;
		$params = $router->getParams();
		if(!empty($params['attributes']['level'])){
			global $project;
			$loginController = $project->getModule('Account')->getController('Account_Controller_LoginController');
			if(!$loginController->is_logined()){
				global $request;
				$request->redirect($request->formatUrl('admin/login'));
			}else{
				
			}
		}elseif($params['attributes']['project'] == 'Admin'){
			$params['attributes']['frame'] = 'Login';
			$params['attributes']['outline'] = 'Login';
			$router->setParams($params);
		}
	}
}