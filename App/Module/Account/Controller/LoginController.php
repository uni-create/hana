<?php
class Account_Controller_LoginController extends Hana_Controller
{
	protected $params = array();
	
	public function is_logined(){
		$loginModel = Hana::getModule('Account')->getModel('Account_Model_Login');
		return $loginModel->is_logined($this->params['cookieName']);
	}
	public function Login(){
		$loginModel = Hana::getModule('Account')->getModel('Account_Model_Login');
		if($loginModel->login()){
			$this->redirect($this->params['redirects']['form']['success']);
		}else{
			//error
		}
	}
	public function setParams($params){
		$this->params = $params;
		$loginModel = Hana::getModule('Account')->getModel('Account_Model_Login');
		$loginModel->setCookieName($params['cookieName']);
	}
	public function login_error_redirect(){
		$this->redirect($this->params['redirects']['logined']['error']);
	}
}