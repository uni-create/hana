<?php
class Hana_Project_Module extends Hana_Project
{
	protected $data = array();

	public function exec($query=null,$data=array(),$viewFlag=true){
		global $view;
		$refView = $view;
		// $view = null;
		
		$this->router->setModuleSet($this->name);
		
		$urls = $this->request->parseUrl($query);
		
		$control = $this->router->getModuleControlSet($urls);
		
		if($viewFlag){
			$viewSet = $this->router->getModuleViewSet($urls);
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