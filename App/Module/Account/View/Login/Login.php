<h1>Login</h1>
<form action="" method="POST">
<?php echo $this->getHash('mod_account_user_login');?>
<label>User</label><input type="text" name="user" value=""><br>
<label>Password</label><input type="password" name="password" value=""><br>
<input type="submit">
</form>