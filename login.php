<?php require 'header.php';?>
<?php
if (isset($_SESSION['id'])) { header('Location: news.php'); }
if (isset($_POST['submit'], $_POST['username'], $_POST['password'])) {
	$user = (string) $_POST['username'];
	$pass = (string) $_POST['password'];
	$hashed_password = password_hash($_POST['password'], PASSWORD_BCRYPT, ['salt'=>'3d9kL01vOpQMTY952Jk13j', 'cost'=>15]);
	$q = $db->prepare("select * from users where username=?");
	$q->bindParam(1, $user);
	$q->execute();
		if ($q->rowCount() > 0) {
			$q->setFetchMode(PDO::FETCH_ASSOC);
			$r = $q->fetch();
			if (password_verify($pass, $r['password'])) {
				$_SESSION['id'] = $r['id'];
				changeTime($db, $_SESSION['id']);
				header('Location: news.php');
			} else {
				echo 'Sorry, your password is not valid.<br /><br />';
			}
		} else {
			echo 'Username does not exist.<br /><br />';
		}
}
?>
<font size="+4">Login</font><br />
<fieldset class="login">
<table border="0">
<form method="post" action="">
<tr><td>Username:</td><td><input type="text" name="username" placeholder="Enter Username" maxlength="60" /></td></tr>
<tr><td>Password:</td><td><input type="password" name="password" placeholder="Enter Password" maxlength="60" /></td></tr>
<tr><td colspan="100%" align="center"><input type="submit" align="middle" name="submit" value="Login" /></td></tr>
</form>
</table><br />
<br />
<a href="forgot.php">Forgot Password?</a> &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp; <a href="register.php">Not Registered yet?</a>
</fieldset>
<?php require 'footer.php';?>