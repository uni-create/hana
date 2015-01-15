<header style="padding:20px;background-color:#F5F5E4;margin-bottom:20px;">
<h1>Load header parts</h1>
<nav>
	<ol>
		<li><a href="<?php echo $this->getUrl('/');?>">top</a></li>
		<li><a href="<?php echo $this->getUrl('next.php');?>">test next</a></li>
		<li><a href="<?php echo $this->getUrl('ttt.php');?>">404</a></li>
		<li><a href="<?php echo $this->getUrl('template.php');?>">template</a></li>
		<li><a href="<?php echo $this->getUrl('module.php');?>">module exec</a></li>
		<li><a href="<?php echo $this->getUrl('admin/');?>">admin project</a></li>
		<li><a href="<?php echo $this->getUrl('admin/test/');?>">admin direct</a></li>
	</ol>
</nav>
</header>