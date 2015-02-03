<h1>Default project Module.php view</h1>
<p>load module exec->account/index</p>
<?php
global $project;
$v = $project->getModule('Account')->exec('',array(),false);
echo $this->data['test'];
var_dump($this->data);
