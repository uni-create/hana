<?php
class Hana_Resource
{
	protected $path = null;
	protected $name = null;
	
	public function setPath($path){
		$this->path = $path;
		//basename??
		return $this;
	}
}