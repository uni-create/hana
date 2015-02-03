<?php
class Hana_Model_Adapters
{
	private static $instance;
	private $adapters = array();
	
	public static function init(){
		if(!self::$instance) self::$instance = new Hana_Model_Adapters();
	}
	public static function get($adapterName){
		$instance = self::getInstance();
		if(empty($instance->adapters[$adapterName])) self::$instance->adapters[$adapterName] = new $adapterName();
		return self::$instance->adapters[$adapterName];
	}
	public static function remove($adapterName){
		$instance = self::getInstance();
		if(self::$instance->adapters[$adapterName]){
			$adapter = self::$instance->adapters[$adapterName];
			unset(self::$instance->adapters[$adapterName]);
			return $adapter;
		}
	}
	public static function getInstance(){
		return self::$instance;
	}
}
Hana_Model_Adapters::init();
