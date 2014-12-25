<?php
class Hana_View_Parts extends Hana_View
{
	protected $name = null;
	
	public function setName($name){
		$this->name = $name;
	}
	public function setView($view){
		$this->view = $view;
	}
}