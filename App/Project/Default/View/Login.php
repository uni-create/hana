<h1>Login default project</h1>
<p>user:a,pass:b</p>
<form action="" method="POST">
<?php echo $this->getHash($this->data['hashName']);?>
<label>User</label><input type="text" name="user" value=""><br>
<label>Password</label><input type="password" name="password" value=""><br>
<input type="submit">
</form>