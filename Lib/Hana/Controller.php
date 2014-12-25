<?php
class Hana_Controller
{
	protected $request = null;
	
	public function setRequest($request){
		$this->request = $request;
	}
}