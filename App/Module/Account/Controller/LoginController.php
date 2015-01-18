<?php
class Account_Controller_LoginController extends Hana_Controller
{
	protected $params = array();
	
	public function is_logined(){
		$loginModel = Hana::module('Account')->getModel('Account_Model_Login');
		return $loginModel->is_logined($this->params['cookieName']);
	}
	public function Login(){
		$loginModel = Hana::module('Account')->getModel('Account_Model_Login');
		if($loginModel->login()){
			$this->redirect($this->params['redirects']['form']['success']);
		}else{
			// var_dump($this);
			//error
		}
	}
	public function Logout(){
		$loginModel = Hana::module('Account')->getModel('Account_Model_Login');
		$loginModel->logout();
		$this->redirect($this->params['redirects']['logout']);
	}
	public function setParams($params){
		$this->params = $params;
		$loginModel = Hana::module('Account')->getModel('Account_Model_Login');
		$loginModel->setCookieName($params['cookieName']);
	}
	public function login_error_redirect(){
		$this->redirect($this->params['redirects']['logined']['error']);
	}
}