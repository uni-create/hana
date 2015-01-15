<?php
class Account_Controller_LoginController extends Hana_Controller
{
	protected $redirects = array();
	
	public function is_logined($cookieName){
		$loginModel = new Account_Model_Login();
		return $loginModel->is_logined($cookieName);
	}
	public function Login(){
		$loginModel = new Account_Model_Login();
		if($loginModel->login()){
			
		}
	}
	public function setRedirectUrls($urls){
		$this->redirect = $urls;
	}
}