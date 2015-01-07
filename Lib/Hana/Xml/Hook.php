<?php
class Hana_Xml_Hook extends Hana_Xml_Reader
{
	public function parseLoop($reader,&$data=array()){
		$reader->read();
		while($reader->read()){
			if($reader->nodeType == XMLReader::ELEMENT){
				$attributes = $this->getAttributes($reader);
				$module = array_key_exists('module',$attributes) ? $attributes['module'] : null;
				$project = array_key_exists('project',$attributes) ? $attributes['project'] : null;
				$name = $reader->name;
				$data[] = array('project'=>$project,'module'=>$module,'name'=>$name,'path'=>$attributes['name']);
			}
		}
	}
}