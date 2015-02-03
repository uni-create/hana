<?php
class Account_Model_Login extends Hana_Model
{
	protected function getCookieName(){
		global $module;
		return Hana::module('Account')->getData('cookieName');
	}
	protected function getHashName($action){
		global $module;
		$hashNames = Hana::module('Account')->getData('hashNames');
		return $hashNames[$action];
	}
	public function is_logined(){
		$cookie = new Hana_Cookie();
		$hash = $cookie->get($this->getCookieName());
		$stmt = $this->db->prepare('select * from mod_account_users where hash = :HASH');
		$stmt->bindValue(':HASH',$hash);
		$stmt->execute();
		return $stmt->fetch();
	}
	public function login(){
		global $request;
		$data = $request->getPost();
		global $view;
		$hashName = $this->getHashName('login');
		$view->setData('hashName',$hashName);
		if(Hana_Hash::is_hash($hashName)){
			if($data){
				$user = $this->get_member($data['user'],$data['password']);
				if($user){
					$hash = Hana_Hash::create_hash();
					$this->update_hash($user['user'],$hash);
					$cookie = new Hana_Cookie();
					$cookie->set($this->getCookieName(),$hash);
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	public function logout(){
		$cookie = new Hana_Cookie();
		$cookie->delete($this->getCookieName());
	}
	public function get_member($user,$password){
		$stmt = $this->db->prepare('select * from mod_account_users where user = :USER and password = :PASSWORD');
		$stmt->bindValue(':USER',$user);
		$stmt->bindValue(':PASSWORD',$password);
		$stmt->execute();
		return $stmt->fetch();
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