<?php
class Hana_Project
{
	protected $name = null;
	protected $modules = array();
	protected $controllers = array();
	protected $models = array();
	protected $data = array();
	
	public function __construct($name){
		$this->name = $name;
		$this->request = new Hana_Request();
		$this->router = new Hana_Router();
	}
	public function setData($name,$data){
		$this->data[$name] = $data;
	}
	public function getData($name){
		return $this->data[$name];
	}
	public function exec($query=null){
		global $project;
		$project = $this;

		global $request;
		$request = $this->request;
		
		global $router;
		$router = $this->router;
		
		$urls = $this->request->parseUrl($this->request->getDefault($query));
		$this->router->init($this->request);
		
		$hooks = $this->router->getHookSet();
		//run hooks
		// var_dump($hooks);
		foreach($hooks as $hookName){
			$h = new $hookName();
			$h->beforeRoute();
		}
		

		global $layout;
		global $view;
		$view = new Hana_View();
		$view->setRoute();
		
		$layout = new Hana_View_Layout();
		$layout->setRoute();
		$layout->init();
		
		$control = $this->router->getControlSet();
		
		
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
			$this->controllers[$controllerName] = new $controllerName();
		}
		return $this->controllers[$controllerName];
	}
	public function getModel($modelName){
		if(empty($this->models[$modelName])){
			$this->models[$modelName] = new $modelName();
		}
		return $this->models[$modelName];
	}
}