<?php
class Hana_View_Layout extends Hana_View
{
	protected $regs = array('HTML_HEAD','HTML_BODY','HTML_FOOTER');
	protected $outlines = array();
	protected $tmp = null;
	
	public function __construct(){
		$this->tmp = new Hana_View_Outline();
	}
	public function setRoute(){
		// $this->router = $router;
		global $router;
		$params = $router->getLayoutSet();
		$this->setPath($params['path']);
		// var_dump($params['path']);
	}
	public function init(){
		global $router;
		$meta = $router->getMeta();
		// $meta = $this->router->getMeta();
		$this->setData('meta',$meta);
		$this->setSource($this->getFileSource());
		
		// $outlines = $this->router->getOutlineSet();
		$outlines = $router->getOutlineSet();
		$this->setOutlines($outlines);
	}
	private function setOutlines($outline){
		$dir = $outline['dir'];
		$tmp = $this->tmp;
		$tmp->setRoute();
		$outlines = array();
		foreach($outline['outlines'] as $reg => $parts){
			$this->addReg('LOAD_'.$reg);
			$otc = clone $tmp;
			$otc->setName($reg);
			$otc->setParts($parts);
			$outlines['LOAD_'.$reg] = $otc;
		}
		$this->outlines = $outlines;
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
		global $view;
		if($this->render && $this->exists){
			$strs = array();
			foreach($this->regs as $key => $reg){
				$strs['{'.$reg.'}'] = '<?php $this->outlines["'.$reg.'"]->render();?>';
			}
			$this->source = strtr($this->source,$strs);
			// var_dump($this->source);
			$this->renderSource();
		}else{
			global $view;
			$view->render();
		}
	}
}