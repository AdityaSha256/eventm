<?php require 'header.php';?>
<?php
if (!isset($_SESSION['id'])) { header('Location: login.php'); }

$id = isset($_GET['id']) ? $id = (int) $_GET['id'] : $id = $_SESSION['id'];
$q = $db->prepare("select * from users where id=?");
$q->bindParam(1, $id);
$q->execute();
$q->setFetchMode(PDO::FETCH_ASSOC);
$r = $q->fetch();
if ($r['id']==NULL) { echo 'This user doesn\'t exist.'; } else {
?>
<table width="85%" style="text-align:center;" class="table">
<?php
echo '
<tr><th colspan="100%">'.$r['username'].'\'s Profile</th></tr>
<tr><td>ID</td><td>#'.$r['id'].'</td></tr>
<tr><td>Full Name</td><td>'.$r['fname'].' '.$r['lname'].'</td></tr>
<tr><td>Gender</td><td>'.$r['gender'].'</td></tr>
<tr><td>Date of Birth</td><td>'.date('d-m-Y',$r['dob']).'</td></tr>
<tr><td>City</td><td>'.$r['city'].'</td></tr>
<tr><td>State</td><td>'.$r['state'].'</td></tr>
<tr><td>Phone Number</td><td>'.$r['phone'].'</td></tr>
<tr><td>E-mail Address</td><td>'.$r['email'].'</td></tr>
';
}
?>
</table>
<?php require 'footer.php';?>
