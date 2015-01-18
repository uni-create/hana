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
				),
				'logout'=>'/'
			),
			'cookieName'=>'hana_admin'
		);
		Hana::module('Account')
						->getController('Account_Controller_LoginController')
						->setParams($params);
		
		
	}
}