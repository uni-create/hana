<?php
class Account_Controller_LoginController extends Hana_Controller
{
	protected function is_logined(){
		$loginModel = Hana::module('Account')->getModel('Login');
		return $loginModel->is_logined();
	}
	public function Secure(){
		global $router;
		$params = $router->getParams();
		if(!empty($params['attributes']['level'])){
			if(!$this->is_logined()){
				$this->login_error_redirect();
			}
		}elseif($params['attributes']['project'] == 'Admin'){
			$params['attributes']['frame'] = 'Login';
			$params['attributes']['outline'] = 'Login';
			$router->setParams($params);
		}
	}
	public function Login(){
		$loginModel = Hana::module('Account')->getModel('Login');
		if($loginModel->login()){
			$redirects = Hana::module('Account')->getData('redirects');
			$this->redirect($redirects['form']['success']);
		}else{
			// var_dump($this);
			//error
		}
	}
	public function Logout(){
		$loginModel = Hana::module('Account')->getModel('Login');
		$loginModel->logout();
		$redirects = Hana::module('Account')->getData('redirects');
		$this->redirect($redirects['logout']);
	}
	protected function login_error_redirect(){
		$module = Hana::module('Account');
		$redirects = Hana::module('Account')->getData('redirects');
		$this->redirect($redirects['logined']['error']);
	}
}