<?php
class Admin_Setting_Main implements Hana_Setting
{
	public function beforeRoute(){
		Hana::module('Account')->getSet('Main')->boot();
	}
}