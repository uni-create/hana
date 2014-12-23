<?php
class Hana_Project
{
	public function __construct(){
		$this->request = new Hana_Request();
		$this->router = new Hana_Router();
		$this->structure = new Hana_Xml_Structure();
	}
	public function exec($query=null){
		$urls = $this->request->parseUrl($this->request->formatUrl($query));
		var_dump($urls);
	}
}