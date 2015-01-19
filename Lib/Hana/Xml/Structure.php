<?php
class Hana_Xml_Structure extends Hana_Xml_Reader
{
	public function parseLoop($reader,&$data=array()){
		// var_dump($reader,$data);
		while($reader->read()){
			if($reader->nodeType == XMLReader::END_ELEMENT) break;
			if($reader->nodeType == XMLReader::ELEMENT){
				$attributes = $this->getAttributes($reader);
				$name = $reader->name;
				if($name == 'File' || $name == 'Directory'){
					$data[$attributes['name']]['type'] = $name;
					$data[$attributes['name']]['attributes'] = $attributes;
					$this->parseLoop($reader,$data[$attributes['name']]);
				}else{
					if($name == 'params'){
						$data['params'] = array();
						$this->parseLoop($reader,$data['params']);
					}elseif($name == 'param'){
						$data[] = array('property'=>$attributes['property'],'value'=>$attributes['value']);
					}else{
						$data[$name] = $attributes;
					}
				}
			}else{
				continue;
			}
		}
	}
	public function getParams($urls){
		// var_dump($urls);
		global $request;
		$data = array();
		$data['urls'] = $urls;
		$data['attributes'] = $this->data['Root']['attributes'];
		$data['project_root'] = array();
		$data['target'] = array();
		$data['paths'] = array();
		$data['path_nodes'] = array();
		$this->searchLoop($this->data['Root'],$urls['directories'],$urls['file'],$data);
		// var_dump($data,$urls,$this->data);
		if($data['attributes']['hook']){
			$data['attributes']['hook'] = preg_split('/,/',$data['attributes']['hook']);
		}else{
			$data['attributes']['hook'] = array();
		}

		if(array_key_exists('joint',$data['attributes'])){
			$joints  = preg_split('/\//',$data['attributes']['joint']);
			$defPaths = array();
			$defPaths = $urls['directories'];
			$defPaths[] = $urls['file'];
			for($i=0;$i<count($data['paths']);$i++){
				array_shift($defPaths);
			}
			if(!end($joints)) array_pop($joints);
			$joints = array_merge($joints,$defPaths);
			$module = array_shift($joints);
			$murls = $request->parseUrl(join('/',$joints));
			$data['attributes']['joint'] = array('name'=>$module,'urls'=>$murls,'type'=>'joint','data'=>array());
		}elseif(array_key_exists('direct',$data['attributes'])){
			$joints  = preg_split('/\//',$data['attributes']['direct']);
			$defPaths = array();
			$defPaths = $urls['directories'];
			$defPaths[] = $urls['file'];
			for($i=0;$i<count($data['paths']);$i++){
				array_shift($defPaths);
			}
			$module = array_shift($joints);
			$murls = $request->parseUrl(join('/',$joints));
			$data['attributes']['direct'] = array('name'=>$module,'urls'=>$murls,'type'=>'direct','data'=>$defPaths);
		}
		if($data['project_root']){
			$ps = $data['project_root'];
			foreach($urls['directories'] as $key => $u){
				if($u == $data['project_root'][$key]){
					array_shift($ps);
				}else{
					break;
				}
			}
			array_unshift($ps,$data['attributes']['project']);
			$data['project_root'] = $ps;
		}
		return $data;
	}
	protected function searchLoop($tree,$directories,$file,&$data=array()){
		if($directories){
			$dir = array_shift($directories);
			if(!empty($tree[$dir])){
				if($tree[$dir]['type'] != 'Directory') return false;
				if(!empty($data['attributes']['joint']) && !empty($tree[$dir]['attributes']['direct'])){
					unset($data['attributes']['joint']);
				}
				if(!empty($data['attributes']['direct']) && !empty($tree[$dir]['attributes']['joint'])){
					unset($data['attributes']['direct']);
				}
				$data['attributes'] = array_merge($data['attributes'],$tree[$dir]['attributes']);
				

				$d = array();
				$d['type'] = $tree[$dir]['type'];
				$d['meta'] = isset($tree[$dir]['meta']) ? $tree[$dir]['meta'] : array();
				$d['params'] = isset($tree[$dir]['params']) ? $tree[$dir]['params'] : array();
				$data['path_nodes'][] = $d;
				$data['paths'][] = $dir;
				if($this->data['Root']['attributes']['project'] != $data['attributes']['project']){
					$data['project_root'] = $data['paths'];
				}
				$this->searchLoop($tree[$dir],$directories,$file,$data);
			}
		}else{
			if(!empty($tree[$file])){
				if($tree[$file]['type'] != 'File') return false;
				$data['attributes'] = array_merge($data['attributes'],$tree[$file]['attributes']);
				$data['target'] = $tree[$file];
				$data['paths'][] = $file;
			}else{
			
			}
		}
	}
}