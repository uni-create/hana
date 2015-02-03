<?php
class Account_Setting_Main implements Hana_ModSetting
{
	public function boot(){
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
			'cookieName'=>'hana_admin',
			'hashNames'=>array('login'=>'mod_account_user_login')
		);
		Hana::module('Account')
								->setData('redirects',$params['redirects'])
								->setData('cookieName',$params['cookieName'])
								->setData('hashNames',$params['hashNames']);
								
		Hana::module('Account')->exec('login/secure',array(),false);
	}
}