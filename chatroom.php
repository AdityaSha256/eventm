<?php require 'header.php'; ?>
<?php
require 'bbcode.php';
if (!isset($_SESSION['id'])) { header('Location: login.php'); }

$t = $db->prepare("select lastseen from users where id=?");
$t->bindParam(1, $_SESSION['id']);
$t->execute();
$lastOnlineTime = $t->fetch(PDO::FETCH_ASSOC)['lastseen'];
if ($lastOnlineTime+10*60< time()) {
	$lol = $db->prepare("delete from chat_online where name=?");
	$lol->bindParam(1, $username);
	$lol->execute();
}


$t = $db->prepare("select username from users where id=?");
$t->bindParam(1, $_SESSION['id']);
$t->execute();
$username = $t->fetch(PDO::FETCH_ASSOC)['username'];
$p = $db->prepare("select * from chat_online where name=?");
$p->bindParam(1, $username);
$p->execute();
if ($p->rowCount() <= 0) {
$l = $db->prepare("insert into chat_online (name) values (?)");
$l->bindParam(1, $username);
$l->execute();
}

if (isset($_POST['submit'], $_POST['text']) && !empty($_POST['text'])) {
	$text = htmlentities($_POST['text'], ENT_QUOTES, 'UTF-8');
	$n = $db->prepare("insert into chat (message, poster) values (?,?)");
	$n->bindParam(1, $text);
	$n->bindParam(2, $username);
	$n->execute();
}
?>


<div id="chatbox">
	<div id="mainchat">
		<?php
			$bb = new BBCode();
			$q = $db->prepare("select * from chat where 1 order by id asc");
			$q->execute();
			while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
				echo '
					<div id="chat">
						<strong>'.$r['poster'].': </strong>
						'.$bb->bbcode($r['message']).'
					</div>
				';
			}
		?>
	</div>
	<div id="userlist">
		<?php
			$q = $db->prepare("select * from chat_online where 1 order by id asc");
			$q->execute();
			while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
				echo '
				<div id="user" ondblclick="location.href=\'pm.php?id='.$r['name'].'\'">'.$r['name'].'</div>
				';
			}
		?>
	</div>
	<div id="postbox"><form method="post" action=""><input type="text" name="text" size="117px" /><input type="submit" name="submit" value="Send" /></form></div>
</div>
<?php require 'footer.php'; ?>