<?php
/*
	$obj->bind('trigger',array($ob,'method'));
*/
class Hana_Observer
{
	protected $binds = array();
	
	public function bind($methodName,$params){
		$this->binds[$methodName][] = $params;
		return $this;
	}
	public function trigger($method,&$res=null){
		if(empty($this->binds[$method])) return $res;
		$binds = $this->binds[$method];
		if($binds){
			foreach($binds as $key => $val){
				if(is_array($val)){
					$obj = $val[0];
					$targetMethod = $val[1];
					if(empty($val[2])) $val[2] = false;
					$unsetFlag = $val[2];
				}else{
					$obj = $this;
					$targetMethod = $val;
				}
				if($obj){
					global $object;
					$refObj = $object;
					$object = $this;
					$obj->$targetMethod($res);
				}
			}
			if($this->binds[$method] && !$unsetFlag) unset($this->binds[$method]);
		}
		return $res;
	}
}