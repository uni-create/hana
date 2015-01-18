<header style="padding:20px;background-color:#F5F5E4;margin-bottom:20px;">
<h1>Load header parts</h1>
<nav>
	<ol>
		<li><a href="<?php echo $this->getUrl('/');?>">top</a></li>
		<li><a href="<?php echo $this->getUrl('admin/');?>">admin</a></li>
		<li><a href="<?php echo $this->getUrl('admin/test/');?>">admin direct</a></li>
		<li><a href="<?php echo $this->getUrl('admin/logout');?>">logout</a></li>
	</ol>
</nav>
</header>