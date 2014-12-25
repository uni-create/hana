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
		
		$urls = $this->request->parseUrl($this->request->getDefault($query));
		$params = $this->router->getParams($this->request);
		// var_dump($params);
		
		//run hooks
		// var_dump($params);
		foreach($params['hookNames'] as $hookName){
			$h = new $hookName();
			if(method_exists($h,'beforeRoute')) $h->beforeRoute($params);
		}
		$control = $this->router->getControl($params);
		
		foreach($params['hookNames'] as $hookName){
			$h = new $hookName();
			if(method_exists($h,'afterRoute')) $h->afterRoute($control);
		}
		

		global $layout;
		global $view;
		$view = new Hana_View();
		$view->setParams($control);
		
		$layout = new Hana_View_Layout();
		$layout->setParams($control);
		

		if(!empty($control['controller']['path'])){
			if(file_exists($control['controller']['path'])){
				$controller = $this->getController($control['controller']['name']);
				$action = $control['controller']['action'];
				if(method_exists($controller,$action)){
					$res = $controller->$action();
				}
			}
		}
		
		if(!$view->isExists() && !$view->isRender()){
			var_dump($control['exception']);
		}
		
		$layout->setView($view);
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