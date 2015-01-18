<?php
class Hana_Request
{
	private $query = null;
	private $urls = array();
	
	public function __construct(){
		$req = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		$this->query = $this->formatUrl(strtr($req,array(BASE=>null)));
		$this->urls = $this->parseUrl($this->query);
	}
	public function getDefault($query=null){
		if($query){
			return $this->formatUrl($query);
		}else{
			return $this->query;
		}
	}
	public function getDefaultUrls($query=null){
		if($query){
			return $this->parseUrl($query);
		}else{
			return $this->urls;
		}
	}
	public function formatUrl($query=null){
		$urls = $this->parseUrl($query);
		$url = BASE;
		if($urls['directories']) $url .= join('/',$urls['directories']).'/';
		if($urls['file'] != 'Index') $url .= $urls['file'];
		if($urls['extension']) $url .= '.'.$urls['extension'];
		if($urls['queries']){
			$url .= '?';
			foreach($urls['queries'] as $name => $q){
				$url .= $name.'='.$q;
			}
		}
		return strtolower($url);
	}
	public function parseUrl($query=null){
		if(strpos($query,'/') === 0){
			$query = ltrim($query,'/');
			if(!$query) $query = 'Index';
		}
		$query = $query ? strtr($query,array(BASE=>null)) : null;
		$info = parse_url($query);
		$paths = preg_split('/\//',$info['path']);
		$paths = array_map('ucfirst',$paths);
		$file = array_pop($paths);
		$ex = null;
		if($file){
			if(strpos($file,'.') !== false) $ex = substr($file,strrpos($file,'.')+1);
			$i = pathinfo($file);
			$file = $i['filename'];
		}else{
			$file = 'Index';
		}
		if(!$paths) $paths = array();
		$urls = array();
		$urls['directories'] = $paths;
		$urls['file'] = $file;
		$urls['extension'] = $ex;
		$urls['queries'] = array();
		if(!empty($info['query'])){
			$queries = preg_split('/\&/',$info['query']);
			while($queries){
				$qs = preg_split('/=/',array_shift($queries));
				$n = array_shift($qs);
				$urls['queries'][$n] = join('=',$qs);
			}
		}
		return $urls;
	}
	public function isAjax(){
		return ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
	}
	public function redirect($url){
		header('Location: '.$url);
		die();
	}
	public function getPost(){
		return $this->sanitize($_POST);
	}
	public function getQuery(){
		return $this->sanitize($_GET);
	}
	private function sanitize($array){
		if(is_array($array)){
			$array = array_map(array($this,'sanitize'),$array);
		}else{
			$array = htmlspecialchars($array);
		}
		return $array;
	}
}