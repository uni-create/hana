<?php
class Hana_View_Outline extends Hana_View
{
	protected $parts = array();
	protected $name = null;
	protected $tmp = null;
	
	public function __construct(){
		$this->tmp = new Hana_View_Parts();
	}
	public function setParams($params){
		$this->dir = $params['dir'];
		$this->parts_dir = $params['parts']['dir'];
	}
	public function setName($name){
		$this->name = $name;
	}
	public function setView($view){
		$this->view = $view;
		foreach($this->parts as $part){
			$part->setView($view);
		}
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
		foreach($this->parts as $part){
			$part->render();
		}
	}
}