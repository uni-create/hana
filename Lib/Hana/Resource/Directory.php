<?php
class Hana_Resource_Directory extends Hana_Resource
{
	protected $childs = array();
	
	public function parse(){
		$res = array();
		if(file_exists($this->path) && is_dir($this->path)){
			$ds = $this->scan();
			foreach($ds as $key => $names){
					$typeObj = $names['type'] == 'directory' ? new Hana_Resource_Directory() : new Hana_Resource_File();
					$typeObj->setPath($names['path']);
					$res[] = $typeObj;
			}
		}
		$this->childs = $res;
	}
	public function get(){
		return $this->childs;
	}
	public function scan(){
		$res = array();
		$ds = scandir($this->path);
		foreach($ds as $key => $name){
			if($name != '.' && $name != '..'){
				$path = $this->path.DIRECTORY_SEPARATOR.$name;
				$res[] = array('name'=>$name,'path'=>$path,'type'=>(is_dir($path)?'directory':'file'));
			}
		}
		return $res;
	}
}