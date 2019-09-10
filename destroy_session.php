<?php include_once 'includes/session.inc.php'?>
<?php

if($_SESSION['remember']){
	$remember = true;
}

unset($_COOKIE[session_name()]);
session_destroy();

session_start();

if($remember) {
	$_SESSION['remember'] = true;
}

header("Location: $referrer", true, 302);

?>