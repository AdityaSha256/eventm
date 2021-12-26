<?php require 'header.php';?>
<?php
if (isset($_SESSION['id'])) { header('Location: eventlist.php'); }

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
			!empty($_POST['fname']) &&
			!empty($_POST['lname']) &&
			!empty($_POST['gender']) &&
			!empty($_POST['dob']) &&
			!empty($_POST['city']) &&
			!empty($_POST['state']) &&
			!empty($_POST['email']) &&
			!empty($_POST['confirm-e']) &&
			!empty($_POST['phone'])
	) {
			$username = (string) $_POST['username'];
			$password = (string) $_POST['password'];
			$cpass = (string) $_POST['confirm-p'];
			$email = (string) $_POST['email'];
			$cemail = (string) $_POST['confirm-e'];
			$fname = (string) $_POST['fname'];
			$lname = (string) $_POST['lname'];
			$gender = (string) $_POST['gender'];
			$city = (string) $_POST['city'];
			$state = (string) $_POST['state'];
			$phone = (int) $_POST['phone'];
			$dob = (int) strtotime($_POST['dob']);
			$stateList = array('Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana','Himachal Pradesh','Jammu & Kashmir','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya', 'Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Tripura','Uttarakhand','Uttar Pradesh','West Bengal','Andaman & Nicobar','Chandigarh','Dadra and Nagar Haveli','Daman & Diu','Delhi','Lakshadweep','Puducherry', 'Telangana');
		if ($password != $cpass) {
			echo 'Password and Confirmation Password do not match.<br /><br />';
		} elseif ($email != $cemail) {
			echo 'E-mail and Confirmation E-mail do not match.<br /><br />';
		} elseif (!in_array($state, $stateList)) {
			echo 'Invalid State.<br /><br />';
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
		$hash = password_hash($_POST['password'], PASSWORD_BCRYPT /*['salt'=>'3d9kL01vOpQMTY952Jk13j', 'cost'=>15]*/);
				$time = time();
				$q = $db->prepare("insert into users (username, password, lname, fname, gender, dob, city, state, phone, email, currency) values (?,?,?,?,?,?,?,?,?,?,'0')");
				$q->bindParam(1, $username);
				$q->bindParam(2, $hash);
				$q->bindParam(3, $lname);
				$q->bindParam(4, $fname);
				$q->bindParam(5, $gender);
				$q->bindParam(6, $dob);
				$q->bindParam(7, $city);
				$q->bindParam(8, $state);
				$q->bindParam(9, $phone);
				$q->bindParam(10, $email);
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
								header('Location: eventlist.php');
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
<tr><td>Gender:</td><td><input type="radio" name="gender" value="male" maxlength="60" />Male
<input type="radio" name="gender" value="female" maxlength="60" />Female
<input type="radio" name="gender" value="other" maxlength="60" />Others</td></tr>
<tr><td>Date of Birth:</td><td><input type="date" name="dob" /></td></tr>
<tr><td>City:</td><td><input type="text" name="city" placeholder="Enter City" maxlength="60" /></td></tr>
<tr><td>State:</td><td>
<select name="state">
	<option value="Andhra Pradesh">Andhra Pradesh</option>
	<option value="Andaman and Nicobar">Andaman and Nicobar Islands</option>
	<option value="Arunachal Pradesh">Arunachal Pradesh</option>
	<option value="Assam">Assam</option>
	<option value="Bihar">Bihar</option>
	<option value="Chandigarh">Chandigarh</option>
	<option value="Chhattisgarh">Chhattisgarh</option>
	<option value="Dadar and Nagar Haveli">Dadar and Nagar Haveli</option>
	<option value="Daman and Diu">Daman and Diu</option>
	<option value="Delhi">Delhi</option>
	<option value="Lakshadweep">Lakshadweep</option>
	<option value="Puducherry">Puducherry</option>
	<option value="Goa">Goa</option>
	<option value="Gujarat">Gujarat</option>
	<option value="Haryana">Haryana</option>
	<option value="Himachal Pradesh">Himachal Pradesh</option>
	<option value="Jammu and Kashmir">Jammu and Kashmir</option>
	<option value="Jharkhand">Jharkhand</option>
	<option value="Karnataka">Karnataka</option>
	<option value="Kerala">Kerala</option>
	<option value="Madhya Pradesh">Madhya Pradesh</option>
	<option value="Maharashtra">Maharashtra</option>
	<option value="Manipur">Manipur</option>
	<option value="Meghalaya">Meghalaya</option>
	<option value="Mizoram">Mizoram</option>
	<option value="Nagaland">Nagaland</option>
	<option value="Odisha">Odisha</option>
	<option value="Punjab">Punjab</option>
	<option value="Rajasthan">Rajasthan</option>
	<option value="Sikkim">Sikkim</option>
	<option value="Tamil Nadu">Tamil Nadu</option>
	<option value="Telangana">Telangana</option>
	<option value="Tripura">Tripura</option>
	<option value="Uttar Pradesh">Uttar Pradesh</option>
	<option value="Uttarakhand">Uttarakhand</option>
	<option value="West Bengal">West Bengal</option>
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
