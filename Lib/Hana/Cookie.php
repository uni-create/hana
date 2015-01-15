<?php
class Hana_Cookie
{
	public function set($cookieName,$value,$sec=null){
		$time = empty($sec) ? time() + 60 * 60 : time() + $sec;
		return setcookie($cookieName,$value,$time,'/');
	}
	public function delete($cookieName){
		return setcookie($cookieName,'',0,'/');
	}
	public function get($cookieName){
		if(!empty($_COOKIE[$cookieName])){
			return $_COOKIE[$cookieName];
		}else{
			return null;
		}
	}
	public function update($cookieName,$sec=null){
		if($sec === null) $sec = 3600;
		$time = time() + $sec;
		$value = $this->get($cookieName);
		return setcookie($cookieName,$value,$time,'/');
	}
}