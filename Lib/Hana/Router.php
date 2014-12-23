<?php
class Hana_Router
{
	protected $main = array();
	protected $module = array();
	
	public function __construct(){
		$main = array();
		$main['dir'] = APP.DIRECTORY_SEPARATOR.'Main';
		$this->main = $main;
		
		$module = array();
		$module['dir'] = APP.DIRECTORY_SEPARATOR.'Module';
		$module['mods'] = array();
		$ms = scandir($module['dir']);
		while($ms){
			$mod = array_shift($ms);
			if($mod != '.' && $mod != '..'){
				$module['mods'][$mod] = array('dir'=>$module['dir'].DIRECTORY_SEPARATOR.$mod);
			}
		}
		$this->module = $module;
	}
}