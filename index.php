<?php require 'header.php';
if (isset($_SESSION['id'])) { header('Location: news.php'); }
?>
Welcome to the Event Management System!
<?php require 'footer.php';?>
