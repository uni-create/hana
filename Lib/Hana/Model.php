<?php
class Hana_Model extends Hana_Observer
{
	protected $db;
	protected $adapter;
	
	public function __construct(){
		$this->setAdapter($this->adapter);
	}
	final function setAdapter($adapterName){
		if($adapterName){
			$this->db = Hana_Model_Adapters::get($adapterName);
		}elseif(defined('ADAPTER')){
			$this->db = Hana_Model_Adapters::get(ADAPTER);
		}
	}
	final public function getAdapter($adapterName){
		return Hana_Model_Adapters::get($adapterName);
	}
	public function getParams($limit,$page=1){
		if(!$page || !is_numeric($page)) $page = 1;
		if(!$limit || !is_numeric($limit)) $limit = 10;
		$params = array();
		$params['start'] = ($page-1) * $limit;
		$params['page'] = $page;
		$params['limit'] = $limit;
		return $params;
	}
	public function getPagenation($count,$params,$minPageCount=10){
		$res = array();
		$startShift = $minPageCount/2;
		$endShift = $minPageCount/2;
		$res['all_count'] = (int)$count;
		$res['all_page'] = ceil($count/$params['limit']);
		if($res['all_page'] == 0) $res['all_page'] = 1;
		$start = $params['page'] - $startShift;
		if($start < 1) $start = 1;
		$res['start'] = $start;
		$end = $params['page'] + $endShift;
		if($end < $minPageCount) $end = $minPageCount;
		if($end > $res['all_page']) $end = $res['all_page'];
		$res['end'] = $end;
		$res['page'] = $params['page'];
		return $res;
	}
}