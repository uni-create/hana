<?php
class Hana_Xml_Outline extends Hana_Xml_Reader
{
	public function parseLoop($reader,&$data=array()){
		$reader->read();
		while($reader->read()){
			if($reader->nodeType == XMLReader::ELEMENT){
				$name = $reader->name;
				$data[$name] = $this->parseParts($reader);
			}
		}
	}
	private function parseParts($reader){
		$parts = array();
		while($reader->read()){
			if($reader->nodeType == XMLReader::END_ELEMENT && $reader->name != 'parts') return $parts;
			if($reader->nodeType == XMLReader::ELEMENT){
				$attributes = $this->getAttributes($reader);
				if(!empty($attributes['name'])) $parts[] = $attributes['name'];
			}
		}
		return $parts;
	}
}