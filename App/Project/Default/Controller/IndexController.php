<?php
class Default_Controller_IndexController extends Hana_Controller
{
	public function Index(){
		global $view;
		$view->setData('test','aaaa');
	}
}