<?php
class Hana_Loader
{
	private static $instance;
	private $paths = array();
	
	private function __construct(){
		spl_autoload_register(array(self,'auto'));
	}
	public static function get(){
		if(self::$instance == null) self::$instance = new self;
		return self::$instance;
	}
	public static function addPath($name,$path){
		self::get()->paths[$name] = $path;
	}
	public static function toLocal($className){
		$instance = self::get();
		$names = preg_split('/_/',$className);
		if($instance->paths[$names[0]] != null){
			$pre = array_shift($names);
			$name = $instance->paths[$pre].DIRECTORY_SEPARATOR.join(DIRECTORY_SEPARATOR,$names).".php";
		}else{
			$name = strtr($className,'_',DIRECTORY_SEPARATOR).'.php';
		}
		return $name;
	}
	private static function auto($className){
		$name = self::toLocal($className);
		if(!file_exists($name)){
			throw new Exception('From AutoLoader. ['.$name.'] is not found.');
		}else{
			$res = include($name);
		}
	}
}