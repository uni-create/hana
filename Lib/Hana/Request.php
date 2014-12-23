<?php
class Hana_Request
{
	private $query = null;
	
	public function __construct(){
		$req = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		$this->query = strtr($req,array(BASE=>null));
	}
	public function formatUrl($query=null){
		
		$urls = $this->parseUrl($query);
		$url = BASE;
		if($urls['directories']) $url .= join('/',$urls['directories']).'/';
		$url .= $urls['file'];
		if($urls['extension']) $url .= '.'.$urls['extension'];
		if($urls['queries']){
			$url .= '?';
			foreach($urls['queries'] as $name => $q){
				$url .= $name.'='.$q;
			}
		}
		return $url;
	}
	public function parseUrl($query){
		$query = $query ? strtr($query,array(BASE=>null)) : $this->query;
		$info = parse_url($query);
		$paths = preg_split('/\//',$info['path']);
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
		if($info['query']){
			$queries = preg_split('/\&/',$info['query']);
			while($queries){
				$qs = preg_split('/=/',array_shift($queries));
				$n = array_shift($qs);
				$urls['queries'][$n] = join('=',$qs);
			}
		}
		return $urls;
	}
}