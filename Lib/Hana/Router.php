<?php
class Hana_Router
{
	protected $project = array();
	protected $module = array();
	protected $settings = array();
	protected $structure = array();
	protected $projectSet = array();
	protected $params = array();
	protected $moduleSet = array();
	
	public function __construct(){
		$settings = array();
		$settings['dir'] = APP.DIRECTORY_SEPARATOR.'Settings';
		$this->settings = $settings;
		require($settings['dir'].DIRECTORY_SEPARATOR.'Config.php');
		
		
		$projects = array();
		$projects['dir'] = APP.DIRECTORY_SEPARATOR.'Project';
		
		$dir = new Hana_Resource_Directory();
		$ds = $dir->setPath($projects['dir'])->scan();
		foreach($ds as $key => $names){
			if($names['type'] == 'directory'){
				$projects['projects'][$names['name']]['dir'] = $names['path'];
				$projects['projects'][$names['name']]['parts'] = $names['path'].DIRECTORY_SEPARATOR.'Parts';
				$projects['projects'][$names['name']]['layout'] = $names['path'].DIRECTORY_SEPARATOR.'Layout';
				Hana_Loader::addPath($names['name'],$names['path']);
			}
		}
		$this->project = $projects;
		
		$module = array();
		$module['dir'] = APP.DIRECTORY_SEPARATOR.'Module';
		$module['modules'] = array();

		$dir = new Hana_Resource_Directory();
		$ds = $dir->setPath($module['dir'])->scan();
		
		while($ds){
			$mod = array_shift($ds);
			$module['modules'][$mod['name']] = $mod['path'];
			Hana_Loader::addPath($mod['name'],$module['modules'][$mod['name']]);
		}
		$this->module = $module;
		

		
		Hana_Loader::addPath('Adapter',APP.DIRECTORY_SEPARATOR.'Adapter');
		
		
		
		// var_dump($this);
	}
	protected function setStructure(){
		$st = new Hana_Xml_Structure();
		$st->setPath($this->settings['dir'].DIRECTORY_SEPARATOR.'Structure.xml');
		$st->init();
		return $st;
	}
	public function init($request){
		$structure = $this->setStructure();
		$params = $structure->getParams($request->getDefaultUrls());
		$projectName = $params['attributes']['project'];
		$this->projectSet = $this->project['projects'][$projectName];
		if(empty($params['attributes']['joint'])) $params['attributes']['joint'] = null;
		if(empty($params['attributes']['direct'])) $params['attributes']['direct'] = null;
		if(empty($params['target']['meta'])) $params['target']['meta'] = array('title'=>null,'description'=>null,'keyword'=>null);
		$this->params = $params;
		// var_dump($params);
	}
	public function getParams(){
		return $this->params;
	}
	public function setParams($params){
		$this->params = $params;
	}
	public function getMeta(){
		return $this->params['target']['meta'];
	}
	public function getSet(){
		$hooks = array();
		$hs = array();
		$prjSet = $this->projectSet;
		$this->params['attributes']['project'];
		$settingPath = $prjSet['dir'].DIRECTORY_SEPARATOR.'Setting'.DIRECTORY_SEPARATOR.'Main.php';
		if(file_exists($settingPath)){
			return array('name'=>'Main','path'=>$settingPath);
		}else{
			return array();
		}
	}
	public function getExceptionSet(){
		$params = $this->params;
		return array(
			'name' => $params['attributes']['exception'],
			'path' => $this->projectSet['parts'].DIRECTORY_SEPARATOR.$params['attributes']['exception'].'.php'
		);
	}
	public function getLayoutSet(){
		$layout = $this->params['attributes']['layout'];
		$frame = $this->params['attributes']['frame'];
		return array(
			'name' => $layout,
			'dir' => $this->projectSet['layout'].DIRECTORY_SEPARATOR.$layout.DIRECTORY_SEPARATOR.'Frame',
			'path' => $this->projectSet['layout'].DIRECTORY_SEPARATOR.$layout.DIRECTORY_SEPARATOR.'Frame'.DIRECTORY_SEPARATOR.$frame.'.php',
		);
	}
	public function getControlSet(){
		$res = array();
		$params = $this->params;
		$projectName = $params['attributes']['project'];
		$prjSet = $this->projectSet;
		
		$moduleData = !empty($params['attributes']['joint']) ? $params['attributes']['joint'] : $params['attributes']['direct'];
		if(array_key_exists($moduleData['name'],$this->module['modules'])){
			$directories = $moduleData['urls']['directories'] ? join('_',$moduleData['urls']['directories']) : 'Index';
			$ds = $moduleData['urls']['directories'] ? join(DIRECTORY_SEPARATOR,$moduleData['urls']['directories']) : 'Index';
			if(isset($params['target']['params'])){
				$params = $params['target']['params'];
			}else{
				if(isset($params['path_nodes'])){
					$last = end($params['path_nodes']);
					$ps = $last['params'];
				}else{
					$ps = array();
				}
			}
			return array(
								'project' => $projectName,
								'module'=>$moduleData['name'],
								'name' => $directories,
								'path' => $this->module['modules'][$moduleData['name']].DIRECTORY_SEPARATOR.'Controller'.DIRECTORY_SEPARATOR.$ds.'Controller.php',
								'action' => $moduleData['urls']['file'],
								'params' => $ps,
								'data' => $moduleData['data']
							);
		}else{
			// var_dump($params['project_root']);
			if(!empty($params['project_root'])){
				$directories = $params['project_root'];
				if($directories[0] == $params['attributes']['project']) array_shift($directories);
			}else{
				$directories = $params['urls']['directories'];
			}
			
			$ds = $directories ? join('_',$directories) : 'Index';
			if(!$directories) $directories[] = 'Index';
			$directories = $directories ? join(DIRECTORY_SEPARATOR,$directories) : 'Index';
			$pm = empty($params['target']['params']) ? array() : $params['target']['params'];
			return array(
									'project' => $projectName,
									'name' => $ds,
									'path' => $prjSet['dir'].DIRECTORY_SEPARATOR.'Controller'.DIRECTORY_SEPARATOR.$directories.'Controller.php',
									'action' => $params['urls']['file'],
									'params' => $pm,
									'data' => array()
								);
		}
	}
	public function getViewSet(){
		$params = $this->params;
		$prjSet = $this->projectSet;
		$moduleData = !empty($params['attributes']['joint']) ? $params['attributes']['joint'] : $params['attributes']['direct'];
		if(!$moduleData){
			if(!empty($params['project_root'])){
				$directories = $params['project_root'];
				if($directories[0] == $params['attributes']['project']) array_shift($directories);
			}else{
				$directories = $params['urls']['directories'];
			}
			$dss = $directories ? join(DIRECTORY_SEPARATOR,$directories).DIRECTORY_SEPARATOR : null;
			return array(
				'name' => $params['urls']['file'],
				'path' => $prjSet['dir'].DIRECTORY_SEPARATOR.'View'.DIRECTORY_SEPARATOR.$dss.$params['urls']['file'].'.php',
			);
		}else{
			if(array_key_exists($moduleData['name'],$this->module['modules'])){
				
				$dss = $moduleData['urls']['directories'] ? join(DIRECTORY_SEPARATOR,$moduleData['urls']['directories']).DIRECTORY_SEPARATOR : null;

				return array(
					'name' => $moduleData['urls']['file'],
					'path' => $this->module['modules'][$moduleData['name']].DIRECTORY_SEPARATOR.'View'.DIRECTORY_SEPARATOR.$dss.$moduleData['urls']['file'].'.php'
				);
			}else{
				return array();
			}
		}
	}
	public function getThemeSet(){
		$theme = $this->params['attributes']['theme'];
		return array(
			'name' => $theme,
			'dir' => ROOT.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.$theme,
			'url' => BASE.'src/theme/'.$theme.'/'
		);
	}
	public function getOutlineSet(){
		$outline = $this->params['attributes']['outline'];
		$layout = $this->params['attributes']['layout'];
		$res = array(
			'name' => $outline,
			'dir' => $this->projectSet['layout'].DIRECTORY_SEPARATOR.$layout.DIRECTORY_SEPARATOR.'Outline',
			'path' => $this->projectSet['layout'].DIRECTORY_SEPARATOR.$layout.DIRECTORY_SEPARATOR.'Outline'.DIRECTORY_SEPARATOR.$outline.'.xml'
		);
		$outlineReader = new Hana_Xml_Outline();
		$outlineReader->setPath($res['path']);
		$outlineReader->init();
		$res['outlines'] = $outlineReader->getData();
		return $res;
	}
	public function getPartsSet(){
		return array(
			'dir' => $this->projectSet['parts']
		);
	}
	public function setModuleSet($moduleName){
		$this->moduleSet['dir'] = $this->module['modules'][$moduleName];
		$this->moduleSet['name'] = $moduleName;
	}
	public function getModuleControlSet($urls){
		$moduleName = $this->moduleSet['name'];
		$res = array();
		$ds = $urls['directories'];
		if(empty($ds)) $ds = array('Index');
		return array(
						'path'=>$this->moduleSet['dir'].DIRECTORY_SEPARATOR.'Controller'.DIRECTORY_SEPARATOR.join('_',$ds).'Controller.php',
						'name'=>join('_',$ds),
						'action'=>$urls['file']
		);
	}
	public function getModuleViewSet($urls){
		$moduleName = $this->moduleSet['name'];
		$vd = empty($urls['directories']) ? null : DIRECTORY_SEPARATOR.join(DIRECTORY_SEPARATOR,$urls['directories']);
		return array(
						'path'=>$this->moduleSet['dir'].DIRECTORY_SEPARATOR.'View'.$vd.DIRECTORY_SEPARATOR.$urls['file'].'.php'
		);
	}
}