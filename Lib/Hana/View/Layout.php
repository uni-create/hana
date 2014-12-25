<?php
class Hana_View_Layout extends Hana_View
{
	protected $regs = array('HTML_HEAD','HTML_BODY','HTML_FOOTER');
	protected $outlines = array();
	protected $tmp = null;
	
	public function __construct(){
		$this->tmp = new Hana_View_Outline();
	}
	public function setParams($params){
		$this->setPath($params['theme']['local']['layout']['path']);
		$this->setData('meta',$params['data']['meta']);
		// var_dump($params);
		$this->setSource($this->getFileSource());
		$this->setOutlines($params['theme']['local']['layout']['outline']);
	}
	public function setView($view){
		$this->view = $view;
		foreach($this->outlines as $outline){
			$outline->setView($view);
		}
	}
	private function setOutlines($outline){
		$dir = $outline['dir'];
		$tmp = $this->tmp;
		$tmp->setParams($outline);
		$outlines = array();
		// var_dump($this->source);
		foreach($outline['outlines'] as $reg => $parts){
			$this->addReg('LOAD_'.$reg);
			$otc = clone $tmp;
			
			$otc->setName($reg);
			$otc->setParts($parts);
			$outlines['LOAD_'.$reg] = $otc;
		}
		$this->outlines = $outlines;
		// var_dump($this);
		// $this->render();
	}
	public function addReg($reg){
		if(!in_array($reg,$this->regs)){
			$this->regs[] = $reg;
		}
	}
	public function getOutline($outlineName){
		return $this->outlines[$outlineName];
	}
	public function createOutline($outlineName,$reg=null){
		$on = $reg ? $reg.'_'.$outlineName : 'LOAD_'.$outlineName;
		$this->addReg($on);
		// $outline = clone 
		$this->outlines[$on] = $outline;
	}
	public function render(){
		if($this->render){
			$strs = array();
			foreach($this->regs as $key => $reg){
				$strs['{'.$reg.'}'] = '<?php $this->outlines["'.$reg.'"]->render();?>';
			}
			$this->source = strtr($this->source,$strs);
			// var_dump($this->source);
			$this->renderSource();
		}else{
			$this->view->render();
		}
	}
}