<?php
class Hana_Timer
{
	public $times = array();
	public $num;
	
	public function __construct($float=null){
		$this->num = (empty($float)) ? 7 : $float;
	}
	public function set(){
		$this->times[] = $this->getMicrotime();
	}

	protected function getMicrotime(){
		list($msec, $sec) = preg_split("/ /", microtime());
		return ((float)$sec + (float)$msec);
	}
	
	public function getTime(){
		if(count($this->times) == 2){
			return array(number_format($this->times[1] - $this->times[0], $this->num));
		}else{
			$def = 0;
			foreach($this->times as $key => $time){
				if($def == 0){
					$def = $time;
					continue;
				}
				$res[] = $time - $def;
				$def = $time;
			}
			return $res;
		}
		
	}
	

}