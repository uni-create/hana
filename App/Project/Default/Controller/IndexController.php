<?php
class Default_Controller_IndexController extends Hana_Controller
{
	public function Index(){
		global $view;
		$view->setData('test','aaaa');
	}
	public function Next(){
		global $view;
		global $project;
		$view->setData('desc','overwide meta title of layout & use model database test.');
		$view->setData('database',$project->getModel('Default')->test2());
		
		global $layout;
		$meta = $layout->getData('meta');
		$meta['title'] = 'overwide meta title of layout & use model database test.';
		$layout->setData('meta',$meta);
	}
	public function Login(){
		Hana::module('Admin')->getSet('Main')->beforeRoute();
		Hana::module('Account')->exec('Login/Login',array(),false);
	}
}