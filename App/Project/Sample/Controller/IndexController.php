<?php
class Sample_Controller_IndexController extends Hana_Controller
{
	public function Index(){
		global $view;
		$view->setData('test','aaaa');
	}
	public function Next(){
		global $view;
		$view->setData('desc','overwide meta title of layout & use model database test.');
		$view->setData('database',Hana::model('Sample_Model_Default')->test2());
		
		global $layout;
		$meta = $layout->getData('meta');
		$meta['title'] = 'overwide meta title of layout & use model database test.';
		$layout->setData('meta',$meta);
	}
	public function Login(){
		Hana::module('Account')->getHook('Account_Setting_Main')->beforeRoute();
		Hana::module('Account')->exec('Login/Login',array(),false);
	}
}