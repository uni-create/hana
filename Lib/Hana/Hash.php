<?php
class Hana_Hash
{
	protected static $posts = array();
	protected static $hashes = array();
	
	public static function create_hash(){
		if(!isset($_SESSION)) session_start();
		return uniqid('',true);
	}
	public static function is_hash($name){
		if(empty(self::$hashes[$name])){
			self::$hashes[$name] = array('name'=>md5(self::create_hash()),'value'=>self::create_hash());
		}else{
			echo '<h1>[Hana_Hash->is_hash(hash_name)]'.$name.' is aleady set.Please set other name.</h1>';
		}
		$refSession = !empty($_SESSION[$name]) ? $_SESSION[$name] : null;
		$post = isset($_POST[$refSession['name']]) ? $_POST[$refSession['name']] : null;
		$_SESSION[$name] = self::$hashes[$name];
		if($refSession['value'] == $post && $refSession['value']){
			return true;
		}else{
			return false;
		}
	}
	public static function get_hash($name){
		if(!empty(self::$hashes[$name])){
			return self::$hashes[$name];
		}else{
			return array();
		}
	}
}