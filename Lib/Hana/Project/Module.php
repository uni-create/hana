<?php
class Hana_Project_Module extends Hana_Project
{
	protected $data = array();

	public function __construct($name){
		$this->name = $name;
		$this->request = new Hana_Request();
	}
	public function exec($query=null,$data=array(),$viewFlag=true){
		global $view;
		$refView = $view;
		// $view = null;
		
		global $router;
		$router->setModuleSet($this->name);
		
		$urls = $this->request->parseUrl($query);
		
		$control = $router->getModuleControlSet($urls);
		
		if($viewFlag){
			$viewSet = $router->getModuleViewSet($urls);
			$view = new Hana_View();
			$view->setPath($viewSet['path']);
		}
		$res = null;
		if(file_exists($control['path'])){
			$controller = $this->getController($control['name']);
			$method = $control['action'];
			if(method_exists($controller,$method)){
				$res = $controller->$method($data);
			}
		}
		$v = $view;
		$view = $refView;
		if($viewFlag){
			return $v;
		}else{
			return $res;
		}
	}
}