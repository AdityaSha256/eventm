<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="Event Management System to create, organise, manage and join events globally, anytime, anywhere, quickly.">
<meta name="keywords" content="event, event management, party, organise">
<meta name="author" content="---">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EMS - Event Management System</title>
<link rel="stylesheet" href="main.css" />
<link rel="shortcut icon" href="images/favicon.ico" />
<link href='https://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'>
</head>

<body>
<center>
<div class="logobox"><div class="logo"></div></div><br />
<br />
<?php
if (!isset($_SESSION['id'])):
echo '
<div class="left">
<a href="index.php"><div class="left-nav">Home</div></a>
<a href="login.php"><div class="left-nav">Login</div></a>
<a href="register.php"><div class="left-nav">Register</div></a>
</div>
<div class="right">
<a href="index.php"><div class="right-nav">Home</div></a>
<a href="login.php"><div class="right-nav">Login</div></a>
<a href="register.php"><div class="right-nav">Register</div></a>
</div>
';
else:
echo '
<div class="left">
<a href="news.php"><div class="left-nav">News</div></a>
<a href="profile.php?id='.$_SESSION['id'].'"><div class="left-nav">Your Profile</div></a>
<a href="edit.php"><div class="left-nav">Edit Profile</div></a>
<a href="logout.php"><div class="left-nav">Logout</div></a>
</div>
<div class="right">
<a href="index.php"><div class="right-nav">Home</div></a>
<a href="login.php"><div class="right-nav">Login</div></a>
<a href="register.php"><div class="right-nav">Register</div></a>
</div>
';
endif;
?>
<div class="content">
<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=eventm', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $r) {
    echo 'Sorry! There was an unexpected error! We are not able to connect to the database because of the following reason:<br /><strong>', $r->getMessage() ,'</strong><br /><br /><br />';
}

//
// Functions
//

function changeTime($db, $id) {
	$time = time();
	$q = $db->prepare("update users set lastseen=? where id=?");
	$q->bindParam(1, $time);
	$q->bindParam(2, $id);
	$q->execute();
}
function lastseen($time) {
   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
   $lengths = array("60","60","24","7","4.35","12","10");

   $now = time();

       $difference     = $now - $time;
       $tense         = "ago";

   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
       $difference /= $lengths[$j];
   }

   $difference = round($difference);

   if($difference != 1) {
       $periods[$j].= "s";
   }

   return "$difference $periods[$j] ago ";
}
if (isset($_SESSION['id'])) {
changeTime($db, $_SESSION['id']);
}
?>
