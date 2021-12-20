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
<tr><td colspan="100%" align="center"><img src="'.$r['avatar'].'" width="125" height="125" /></td></tr>
<tr><td>ID</td><td>#'.$r['id'].'</td></tr>
<tr><td>Rank</td><td>'.$r['rank'].'</td></tr>
<tr><td>Coins</td><td>$'.$r['coins'].'</td></tr>
<tr><td>Last Seen</td><td>'.lastseen($r['lastseen']).'</td></tr>
<tr><td colspan="100%">Signature:<br /><br />'.nl2br($r['signature']).'</td></tr>
<tr><td colspan="100%" align="center"><a href="battle.php?id='.$r['id'].'&battle=trainer">Battle '.$r['username'].'</a></td></tr>
<tr><td colspan="100%" align="center"><a href="pm.php?id='.$r['username'].'">Message '.$r['username'].'</a></td></tr>
<tr><td colspan="100%" align="center"><a href="trade.php?id='.$r['id'].'">Trade '.$r['username'].'</a></td></tr>
';
}
?>
</table>
<?php require 'footer.php';?>