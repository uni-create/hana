<?php
class Hana_Controller
{
	public function redirect($path){
		global $request;
		$request->redirect($request->formatUrl($path));
	}
}