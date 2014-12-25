<?php
class Hana_Xml_Hook extends Hana_Xml_Reader
{
	public function parseLoop($reader,&$data=array()){
		$reader->read();
		while($reader->read()){
			if($reader->nodeType == XMLReader::ELEMENT){
				$attributes = $this->getAttributes($reader);
				$module = array_key_exists('module',$attributes) ? $attributes['module'] : null;
				$name = $reader->name;
				$data[] = array('module'=>$module,'name'=>$name,'path'=>$attributes['name']);
			}
		}
	}
}