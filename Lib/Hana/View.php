<?php
class Hana_View extends Hana_Observer
{
	protected $path = null;
	protected $exists = false;
	protected $render = true;
	protected $sourceMode = false;
	protected $source = null;
	protected $data = array();
	
	public function setPath($path){
		$this->path = $path;
		$this->exists = file_exists($path);
		$this->sourceMode = false;
	}
	public function isExists(){
		return $this->exists;
	}
	public function isRender(){
		return $this->render;
	}
	public function hide(){
		$this->render = false;
	}
	public function getTheme($name=null){
		global $router;
		$theme = $router->getThemeSet();
		if($name){
			return $theme[$name];
		}else{
			return $theme;
		}
	}
	public function setRoute(){
		// $this->router = $router;
		global $router;
		$params = $router->getViewSet();
		$this->setPath($params['path']);
	}
	public function setSource($source){
		$this->source = $source;
		$this->sourceMode = true;
	}
	public function setData($name,$data){
		$this->data[$name] = $data;
	}
	public function getData($name){
		return $this->data[$name];
	}
	public function getUrl($query=null){
		global $request;
		return $request->getDefault($query);
	}
	
	
	public function render(){
		global $view;
		if($this->render){
			$this->trigger('beforeRender');
			if($this->sourceMode){
				$this->renderSource();
			}else{
				if($this->exists){
					include($this->path);
				}else{
					header("HTTP/1.0 404 Not Found");
				}
			}
			$this->trigger('afterRender');
		}else{
			
		}
	}
    public function getFileSource(){
        if($this->exists){
            return file_get_contents($this->path);
        }else{
            return null;
        }
    }
    public function getRenderSource(){
		$source = $this->source;
        if($source){
            ob_start();
            eval('?>'.$source);
            $source = ob_get_contents();
            ob_end_clean();
            return $source;
        }else{
            return null;
        }
    }
    public function getRenderFile(){
		$source = $this->getFileSource();
        if($source){
            ob_start();
            eval('?>'.$source);
            $source = ob_get_contents();
            ob_end_clean();
            return $source;
        }else{
            return null;
        }
    }
    public function renderSource(){
		$source = $this->source;
        if($source){
            ob_start();
            eval('?>'.$source);
            flush();
            ob_flush();
			return true;
        }else{
			return false;
		}
    }
}
?>