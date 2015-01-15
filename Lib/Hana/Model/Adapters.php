<?php
class Hana_Model_Adapters
{
	private static $instance;
	private static $adapters = array();
	
	public static function init(){
		if(!self::$instance) self::$instance = new Hana_Model_Adapters();
	}
	public static function get($adapterName){
		if(empty(self::$instance->adapters[$adapterName])) self::$instance->adapters[$adapterName] = new $adapterName();
		return self::$instance->adapters[$adapterName];
	}
	public static function remove($adapterName){
		if(self::$instance->adapters[$adapterName]){
			$adapter = self::$instance->adapters[$adapterName];
			unset(self::$instance->adapters[$adapterName]);
			return $adapter;
		}
	}
	public static function getInstance(){
		return self::$instance->adapters;
	}
}
Hana_Model_Adapters::init();
