<?php
class Sample_Model_Default extends Hana_Model
{
	public function test(){
		return $this->db->getTable('mod_account_users');
	}
	public function test2(){
		$stmt = $this->db->prepare('select * from test');
		$stmt->execute();
		return $stmt->fetchAll();
	}
}