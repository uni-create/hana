<?php
class Hana_Xml_Reader
{
	protected $libxml = LIBXML_NOBLANKS;
	protected $reader;
	protected $data = array();
	protected $path;
	
	public function setPath($path){
		$this->path = $path;
	}
	public function init(){
		$path = $this->path;
		if(file_exists($path)){
			$this->path = $path;
			$reader = new XMLReader();
			$reader->open($path,null,$this->libxml);
			$this->reader = $reader;
			$this->parse();
			return true;
		}else{
			return false;
		}
	}
	public function parse($return=false){
		$data = array();
		if($this->reader){
			$this->parseLoop($this->reader,$data);
		}
		$this->data = $data;
	}
	protected function parseLoop($reader,&$data=array()){}
	protected function getAttributes($reader){
		$attributes = array();
		while($reader->moveToNextAttribute()){
			$attributes[$reader->name] = $reader->value;
		}
		$reader->moveToElement();
		return $attributes;
	}
	public function getData(){
		return $this->data;
	}
}