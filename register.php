<?php require 'header.php';?>
<?php
if (isset($_SESSION['id'])) { header('Location: news.php'); }

if (
	(
		isset(
		$_POST['submit'],
		$_POST['username'],
		$_POST['password'],
		$_POST['confirm-p'],
		$_POST['fname'],
		$_POST['lname'],
		$_POST['gender'],
		$_POST['dob'],
		$_POST['city'],
		$_POST['state'],
		$_POST['email'],
		$_POST['confirm-e'],
		$_POST['phone']
		)
	)
) {
	if (	!empty($_POST['username']) &&
			!empty($_POST['password']) &&
			!empty($_POST['confirm-p']) &&
			!empty($_POST['confirm-e']) &&
			!empty($_POST['email']) &&
			!empty($_POST['avatar']) &&
			!empty($_POST['starter'])
	) {
			$username = (string) $_POST['username'];
			$password = (string) $_POST['password'];
			$cpass = (string) $_POST['confirm-p'];
			$email = (string) $_POST['email'];
			$cemail = (string) $_POST['confirm-e'];
			$starter = (string) $_POST['starter'];
			$avatar = (int) $_POST['avatar'];
			$a = $db->prepare("select * from starters");
			$a->execute();
			$starterList = $a->fetch(PDO::FETCH_NUM);
		if ($password != $cpass) {
			echo 'Password and Confirmation Password do not match.<br /><br />';
		} elseif ($email != $cemail) {
			echo 'E-mail and Confirmation E-mail do not match.<br /><br />';
		} elseif (!in_array($starter, $starterList)) {
			echo 'Illegal Starter.<br /><br />';
		} elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
			echo 'Invalid E-mail.<br /><br />';
		} else {
			$t = $db->prepare("select * from users where username=?");
			$t->bindParam(1, $username);
			$t->execute();
			if ($t->rowCount() > 0) {
				echo 'Username already taken!<br /><br />';
			} else {
			//Register
				$hash = password_hash($_POST['password'], PASSWORD_BCRYPT, ['salt'=>'3d9kL01vOpQMTY952Jk13j', 'cost'=>15]);
				$time = time();
				$q = $db->prepare("insert into users (username, password, rank, email, avatar, signature, banner, coins, lastseen) values (?,?,'Member',?,?,'None','No','0',?)");
				$q->bindParam(1, $username);
				$q->bindParam(2, $hash);
				$q->bindParam(3, $email);
				$q->bindParam(4, $avatar);
				$q->bindParam(5, $time);
				$q->execute();
						//Now login
						$q = $db->prepare("select * from users where username=?");
						$q->bindParam(1, $username);
						$q->execute();
						if ($q->rowCount() > 0) {
							$q->setFetchMode(PDO::FETCH_ASSOC);
							$r = $q->fetch();
							if (password_verify($password, $r['password'])) {
								$_SESSION['id'] = $r['id'];
								header('Location: news.php');
							} else {
								echo 'We tried to log you in after registering but it looks like your password is not valid.<br /><br />';
							}
						} else {
							echo 'We tried to log you in after registering, but it looks like something went wrong!<br /><br />';
						}
			}
		}
	} else {
		echo 'Please fill in all the fields before registering.<br /><br />';
	}
}
?>
<font size="+4">Register</font><br />
<fieldset class="login">
<table border="0">
<form method="post" action="">
<tr><td>Username:</td><td><input type="text" name="username" placeholder="Enter Username" maxlength="60" /></td></tr>
<tr><td>First Name:</td><td><input type="text" name="fname" placeholder="Enter First Name" maxlength="60" /></td></tr>
<tr><td>Last Name:</td><td><input type="text" name="lname" placeholder="Enter Last Name" maxlength="60" /></td></tr>
<tr><td>Password:</td><td><input type="password" name="password" placeholder="Enter Password" maxlength="60" /></td></tr>
<tr><td>Confirm Password:</td><td><input type="password" name="confirm-p" placeholder="Confirm Password" maxlength="60" /></td></tr>
<tr><td rowspan="3">Gender:</td><td><input type="radio" name="gender" value="male" maxlength="60" />Male</td>
<td><input type="radio" name="gender" value="female" maxlength="60" />Female</td>
<td><input type="radio" name="gender" value="other" maxlength="60" />Others</td></tr>
<tr><td>Date of Birth:</td><td><input type="text" name="username" placeholder="Enter Username" maxlength="60" /></td></tr>
<tr><td>City:</td><td><input type="text" name="username" placeholder="Enter Username" maxlength="60" /></td></tr>
<tr><td>State:</td><td>
<select name="state">
	<optgroup label="Andhra Pradesh">Andhra Pradesh</optgroup>
		<option value="Bulbasaur">Bulbasaur</option>
		<option value="Charmander">Charmander</option>
		<option value="Squirtle">Squirtle</option>
	<optgroup label="Johto">Johto</optgroup>
		<option value="Chikorita">Chikorita</option>
		<option value="Cyndaquil">Cyndaquil</option>
		<option value="Totodile">Totodile</option>
	<optgroup label="Hoenn">Hoenn</optgroup>
		<option value="Treecko">Treecko</option>
		<option value="Torchic">Torchic</option>
		<option value="Mudkip">Mudkip</option>
	<optgroup label="Sinnoh">Sinnoh</optgroup>
		<option value="Turtwig">Turtwig</option>
		<option value="Chimchar">Chimchar</option>
		<option value="Piplup">Piplup</option>
	<optgroup label="Unova">Unova</optgroup>
		<option value="Snivy">Snivy</option>
		<option value="Tepig">Tepig</option>
		<option value="Oshawott">Oshawott</option>
	<optgroup label="Kalos">Kalos</optgroup>
		<option value="Chespin">Chespin</option>
		<option value="Fennekin">Fennekin</option>
		<option value="Froakie">Froakie</option>
</select>
</td></tr>
<tr><td>Phone Number:</td><td><input type="tel" name="phone" placeholder="Enter Phone Number" maxlength="15" /></td></tr>
<tr><td>E-mail:</td><td><input type="email" name="email" placeholder="Enter E-mail" maxlength="60" /></td></tr>
<tr><td>Confirm E-mail:</td><td><input type="email" name="confirm-e" placeholder="Confirm E-mail" maxlength="60" /></td></tr>
<tr><td colspan="100%" align="center"><input type="submit" align="middle" name="submit" value="Register" /></td></tr>
</form>
</table><br />
<br />
<a href="login.php">Already registered?</a>
</fieldset>
<?php require 'footer.php';?>
