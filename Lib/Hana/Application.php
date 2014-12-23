<?php
class Hana_Application
{
	public function __construct(){
		if(!defined('ROOT')){
			$docroot = dirname($_SERVER['SCRIPT_FILENAME']);
			define('ROOT',strtr($docroot,array('/'=>DIRECTORY_SEPARATOR)));
		}
		if(!defined('BASE')){
			$base = 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']).'/';
			define('BASE',$base);
		}
		require(ROOT.DIRECTORY_SEPARATOR.'Lib'.DIRECTORY_SEPARATOR.'Hana'.DIRECTORY_SEPARATOR.'Loader.php');
		Hana_Loader::addPath('Hana',ROOT.DIRECTORY_SEPARATOR.'Lib'.DIRECTORY_SEPARATOR.'Hana');
	}
	public function run(){
		$this->appPath();
		$project = new Hana_Project();
		$project->exec();
	}
	public function appPath($path=null){
		if(!defined('APP')) define('APP',ROOT.DIRECTORY_SEPARATOR.'App');
	}
}