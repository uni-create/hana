<?php
class Hana_View_Outline extends Hana_View
{
	protected $parts = array();
	protected $name = null;
	protected $tmp = null;
	
	public function __construct(){
		$this->tmp = new Hana_View_Parts();
	}
	public function setRoute(){
		// $this->router = $router;
		global $router;
		$params = $router->getPartsSet();
		$this->parts_dir = $params['dir'];
	}
	public function setName($name){
		$this->name = $name;
	}
	public function getParts($partsName){
		foreach($this->parts as $part){
			if($part->isName($partsName)) return $part;
		}
		return null;
	}
	public function addParts($part,$index=null){
		if(is_numeric($index)){
			//array_splice
		}else{
			$this->parts[] = $part;
		}
	}
	public function setParts($parts){
		$tmp = $this->tmp;
		$tmp->setRoute();
		$ps = array();
		foreach($parts as $part){
			$p = clone $tmp;
			$p->setName($part);
			$p->setPath($this->parts_dir.DIRECTORY_SEPARATOR.$part.'.php');
			$ps[] = $p;
		}
		$this->parts = $ps;
	}
	public function render(){
		global $view;
		foreach($this->parts as $part){
			$part->render();
		}
	}
}