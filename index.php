<?php require 'header.php';
if (isset($_SESSION['id'])) { header('Location: eventlist.php'); }
?>
Welcome to the Event Management System!
<?php require 'footer.php';?>
