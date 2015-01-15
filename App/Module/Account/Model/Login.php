<?php
class Account_Model_Login extends Hana_Model
{
	protected $adapter = 'Default_Model_Adapter_Main';
	protected $hashNames = array(
							'login'=>'mod_account_user_login'
	);
	protected $cookieName = null;
	
	public function setCookieName($name){
		$this->cookieName = $name;
	}
	public function is_logined(){
		$cookie = new Hana_Cookie();
		$hash = $cookie->get($this->cookieName);
		$stmt = $this->db->prepare('select * from mod_account_users where hash = :HASH');
		$stmt->bindValue(':HASH',$hash);
		$stmt->execute();
		return $stmt->fetch();
	}
	public function login(){
		global $request;
		$data = $request->getPost();
		if($data){
			$user = $this->get_member($data['user'],$data['password']);
			if($user){
				$hash = Hana_Hash::create_hash();
				$this->update_hash($user['user'],$hash);
				$cookie = new Hana_Cookie();
				$cookie->set($this->cookieName,$hash);
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	public function get_member($user,$password){
		if(Hana_Hash::is_hash($this->hashNames['login'])){
			$stmt = $this->db->prepare('select * from mod_account_users where user = :USER and password = :PASSWORD');
			$stmt->bindValue(':USER',$user);
			$stmt->bindValue(':PASSWORD',$password);
			$stmt->execute();
			return $stmt->fetch();
		}else{
			return array();
		}
	}
	public function update_hash($user,$hash){
		try{
			$this->db->beginTransaction();
			$stmt = $this->db->prepare('update mod_account_users set hash = :HASH where user = :USER');
			$stmt->bindValue(':HASH',$hash);
			$stmt->bindValue(':USER',$user['user']);
			$stmt->execute();
			$this->db->commit();
		}catch(PDOException $e){
			var_dump($e);
			$this->db->rollback();
		}
	}
}