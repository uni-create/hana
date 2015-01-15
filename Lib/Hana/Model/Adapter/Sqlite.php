<?php
class Hana_Model_Adapter_Sqlite extends Hana_Model_Adapter
{
	public function __construct(){
		$this->init();
	}
	public function init(){
		if($this->params['dbname']){
			parent::__construct('sqlite:'.DATA.DIRECTORY_SEPARATOR.$this->params['dbname']);
			parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			parent::setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
		}
	}
	public function getTable($tableName){
		return new Hana_Model_Table_Sqlite($tableName,$this);
	}
	public function getTables(){
		$stmt = $this->prepare('select name from sqlite_master where type=:TYPE');
		$stmt->bindValue(':TYPE','table');
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$res = array();
		foreach($result as $key => $val){
			$res[] = $val["name"];
		}
		return $res;
	}
	public function explain($sql){
		return $this->query('explain '.$sql)->fetchAll(PDO::FETCH_ASSOC);
	}
}