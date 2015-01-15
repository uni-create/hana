<?php
class Default_Model_Default extends Hana_Model
{
	protected $adapter = 'Default_Model_Adapter_Main';
	
	public function test(){
		return $this->db->getTable('mod_account_users');
	}
}