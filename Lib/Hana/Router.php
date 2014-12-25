<?php
class Hana_Router
{
	protected $main = array();
	protected $module = array();
	protected $settings = array();
	protected $parts = array();
	protected $theme = array();
	
	public function __construct(){
		$main = array();
		$main['dir'] = APP.DIRECTORY_SEPARATOR.'Main';
		$this->main = $main;
		Hana_Loader::addPath('Main',$main['dir']);
		
		$module = array();
		$module['dir'] = APP.DIRECTORY_SEPARATOR.'Module';
		$module['modules'] = array();
		$ms = scandir($module['dir']);
		while($ms){
			$mod = array_shift($ms);
			if($mod != '.' && $mod != '..'){
				$module['modules'][$mod] = $module['dir'].DIRECTORY_SEPARATOR.$mod;
				Hana_Loader::addPath($mod,$module['modules'][$mod]);
			}
		}
		$this->module = $module;
		
		$settings = array();
		$settings['dir'] = APP.DIRECTORY_SEPARATOR.'Settings';
		$this->settings = $settings;
		
		$parts = array();
		$parts['dir'] = APP.DIRECTORY_SEPARATOR.'Parts';
		$this->parts = $parts;
		
		$theme = array();
		$theme['dir'] = APP.DIRECTORY_SEPARATOR.'Theme';
		$this->theme = $theme;
		
		$this->setStructure();
	}
	protected function setStructure(){
		$st = new Hana_Xml_Structure();
		$st->setPath($this->settings['dir'].DIRECTORY_SEPARATOR.'Structure.xml');
		$st->init();
		$this->structure = $st;
	}
	public function getParams($request){
		$params = $this->structure->getParams($request);
		$params['hookNames'] = array();
		$hook = new Hana_Xml_Hook();
		$hs = array();
		foreach($params['attributes']['hook'] as $hname){
			$hpath = $this->theme['dir'].DIRECTORY_SEPARATOR.$params['attributes']['theme'].DIRECTORY_SEPARATOR.'Hook'.DIRECTORY_SEPARATOR.$hname.'.xml';
			$h = clone $hook;
			$h->setPath($hpath);
			$h->init();
			$hs = $hs + $h->getData();
		}
		if($hs){
			foreach($hs as $key => $h){
				if($h['module']){
					$hname = ucfirst($h['module']).'_Hook_'.ucfirst(strtr($h['path'],array('/'=>'_')));
				}else{
					$hname = 'Main_Hook_'.ucfirst(strtr($h['path'],array('/'=>'_')));
				}
				$params['hookNames'][] = $hname;
			}
		}
		
		return $params;
	}
	public function getControl($params=array()){
		$res = array();
		$res['data'] = array('meta'=>null,'params'=>array(),'path'=>array());
		if($params['target']){
			$res['data']['meta'] = $params['target']['meta'];
			$res['data']['params'] = $params['target']['params'];
			$res['data']['path'] = $params['path_nodes'];
		}
		$res['theme'] = array();
		$res['theme']['resource'] = array(
							'name' => $params['attributes']['theme'],
							'dir' => ROOT.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.$params['attributes']['theme'],
							'url' => BASE.'src/'.$params['attributes']['theme'].'/'
						);
		
						
		$res['theme']['local']['layout'] = array();
		$res['theme']['local']['layout'] = array(
							'name' => $params['attributes']['layout'],
							'dir' => APP.DIRECTORY_SEPARATOR.'Theme'.DIRECTORY_SEPARATOR.$params['attributes']['theme'].DIRECTORY_SEPARATOR.'Layout',
							'path' => APP.DIRECTORY_SEPARATOR.'Theme'.DIRECTORY_SEPARATOR.$params['attributes']['theme'].DIRECTORY_SEPARATOR.'Layout'.DIRECTORY_SEPARATOR.$params['attributes']['layout'].'.php',
						);
		$res['theme']['local']['layout']['outline'] = array();
		$res['theme']['local']['layout']['outline'] = array(
							'name' => $params['attributes']['outline'],
							'dir' => APP.DIRECTORY_SEPARATOR.'Theme'.DIRECTORY_SEPARATOR.$params['attributes']['theme'].DIRECTORY_SEPARATOR.'Outline',
							'parts' => array('dir'=>$this->parts['dir']),
							'path' => APP.DIRECTORY_SEPARATOR.'Theme'.DIRECTORY_SEPARATOR.$params['attributes']['theme'].DIRECTORY_SEPARATOR.'Outline'.DIRECTORY_SEPARATOR.$params['attributes']['outline'].'.xml'
						);
		$outlinePath = $res['theme']['local']['layout']['outline']['path'];
		$outlineReader = new Hana_Xml_Outline();
		$outlineReader->setPath($outlinePath);
		$outlineReader->init();
		$res['theme']['local']['layout']['outline']['outlines'] = $outlineReader->getData();
		
		
		$res['exception'] = array(
							'name' => $params['attributes']['exception'],
							'path' => $this->parts['dir'].DIRECTORY_SEPARATOR.$params['attributes']['exception'].'.php'
						);
		
		$res['controller'] = array();
		if(!empty($params['attributes']['joint']) || !empty($params['attributes']['direct'])){//!$params['target']??
			$moduleData = !empty($params['attributes']['joint']) ? $params['attributes']['joint'] : $params['attributes']['direct'];
			if(array_key_exists($moduleData['name'],$this->module['modules'])){
				$directories = $moduleData['urls']['directories'] ? join('_',$moduleData['urls']['directories']) : 'Index';
				$ds = $moduleData['urls']['directories'] ? join(DIRECTORY_SEPARATOR,$moduleData['urls']['directories']) : 'Index';
				$dss = $moduleData['urls']['directories'] ? join(DIRECTORY_SEPARATOR,$moduleData['urls']['directories']).DIRECTORY_SEPARATOR : null;
				$res['controller'] = array(
									'name' => $moduleData['name'].'_Controller_'.$directories.'Controller',
									'path' => $this->module['modules'][$moduleData['name']].DIRECTORY_SEPARATOR.'Controller'.DIRECTORY_SEPARATOR.$ds.'Controller.php',
									'action' => $moduleData['urls']['file'],
									'data' => $moduleData['params']
								);
				$res['view'] = array(
									'name' => $moduleData['urls']['file'],
									'path' => $this->module['modules'][$moduleData['name']].DIRECTORY_SEPARATOR.'View'.DIRECTORY_SEPARATOR.$dss.$moduleData['urls']['file'].'.php',
								);
			}
		}
		if(!$res['controller']){
			$directories = $params['urls']['directories'] ? join('_',$params['urls']['directories']) : 'Index';
			$ds = $params['urls']['directories'] ? join(DIRECTORY_SEPARATOR,$params['urls']['directories']) : 'Index';
			$dss = $params['urls']['directories'] ? join(DIRECTORY_SEPARATOR,$params['urls']['directories']).DIRECTORY_SEPARATOR : null;
			$res['controller'] = array(
									'name' => 'Main_Controller_'.$directories.'Controller',
									'path' => $this->main['dir'].DIRECTORY_SEPARATOR.'Controller'.DIRECTORY_SEPARATOR.$ds.'Controller.php',
									'action' => $params['urls']['file']
								);
			$res['view'] = array(
								'name' => $params['urls']['file'],
								'path' => $this->main['dir'].DIRECTORY_SEPARATOR.'View'.DIRECTORY_SEPARATOR.$dss.$params['urls']['file'].'.php',
							);
		}

		
		return $res;
	}
}