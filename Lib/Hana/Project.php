<?php
class Hana_Project
{
	protected $name = null;
	protected $modules = array();
	protected $controllers = array();
	protected $models = array();
	protected $data = array();
	protected $configs = array();
	
	public function __construct($name){
		$this->name = $name;
	}
	public function setData($name,$data){
		$this->data[$name] = $data;
		return $this;
	}
	public function getData($name){
		return $this->data[$name];
	}
	public function exec($query=null){
		global $project;
		$project = $this;

		global $request;
		global $router;
		
		$urls = $request->parseUrl($request->getDefault($query));
		$router->init($request);
		
		$control = $router->getControlSet();
		// var_dump($control);
		if(isset($control['project'])) $this->name = $control['project'];
		
		$set = $router->getSet();
		

		if($set){
			$this->getSet($set['name'])->beforeRoute();
		}
	

		global $layout;
		global $view;
		$view = new Hana_View();
		$view->setRoute();
		
		$layout = new Hana_View_Layout();
		$layout->setRoute();
		$layout->init();
		

		
		if(!empty($control['path'])){
			if(file_exists($control['path'])){
				if(empty($control['module'])){
					$controller = $this->getController($control['name']);
				}else{
					$module = $this->getModule($control['module']);
					$controller = $module->getController($control['name']);
				}
				$action = $control['action'];
				if(method_exists($controller,$action)){
					$res = $controller->$action($control['params'],$control['data']);
				}
			}
		}

		if(!$view->isExists() && $view->isRender()){
			$exParams = $router->getExceptionSet();
			$view->setPath($exParams['path']);
		}
		// var_dump($layout);
		$layout->render();
	}
	public function getModule($moduleName){
		if(empty($this->modules[$moduleName])){
			$this->modules[$moduleName] = new Hana_Project_Module($moduleName);
		}
		return $this->modules[$moduleName];
	}
	public function getController($controllerName){
		if(empty($this->controllers[$controllerName])){
			$name = $this->name.'_Controller_'.$controllerName.'Controller';
			$this->controllers[$controllerName] = new $name();
		}
		return $this->controllers[$controllerName];
	}
	public function getModel($modelName){
		if(empty($this->models[$modelName])){
			$name = $this->name.'_Model_'.$modelName;
			$this->models[$modelName] = new $name();
		}
		return $this->models[$modelName];
	}
	public function getSet($setName){
		if(empty($this->sets[$setName])){
			$name = $this->name.'_Setting_'.$setName;
			$this->sets[$setName] = new $name();
		}
		return $this->sets[$setName];
	}
}