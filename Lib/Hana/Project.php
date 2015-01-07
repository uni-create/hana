<?php
class Hana_Project
{
	protected $modules = array();
	protected $controllers = array();
	protected $models = array();
	
	public function __construct(){
		$this->request = new Hana_Request();
		$this->router = new Hana_Router();
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
		// var_dump($params);
		foreach($hooks as $hookName){
			$h = new $hookName();
			if(method_exists($h,'beforeRoute')) $h->beforeRoute();
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
				$controller = $this->getController($control['name']);
				$action = $control['action'];
				if(method_exists($controller,$action)){
					$res = $controller->$action();
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
	}
}