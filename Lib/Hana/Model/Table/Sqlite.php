<?php
class Hana_Model_Table_Sqlite
{
	protected $name;
	protected $adapter;
	protected $exists = false;
	
	public function __construct($name,$adapter){
		$this->adapter = $adapter;
		$this->name = $name;
		$this->exists = $this->isTable();
	}
	public function isTable(){
		$stmt = $this->adapter->prepare('select name from sqlite_master where type=:TYPE and name=:NAME');
		$stmt->bindValue(':TYPE','table');
		$stmt->bindValue(':NAME',$this->name);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result === false ? false : true;
	}
	public function getColumns(){
		
	}
}