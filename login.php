<?php include_once 'includes/session.inc.php'?>
<?php
// Login!

if($_SERVER['REQUEST_METHOD'] != "POST") {
	header("Location: $referrer", true, 302);
	die();
}

$username = $password = $remember = "";

if(isset($_POST['remember'])) {
	$remember = true;
}

if($_POST['username'] != ""){
	// This can be either an email or a username. Check for email first.
	preg_match("/^([\w\-\.]+)@((\[([0-9]{1,3}\.){3}[0-9]{1,3}\])|(([\w\-]+\.)+)([a-zA-Z]{2,4}))$/", $_POST['username'], $emailmatch);
	if($emailmatch != []){  // Email matched! The user typed an email.
		$username = $emailmatch[0];
	} else {  // Not an email, but also not empty. Probably the username!
		$username = $_POST['username'];
	}
} else
	$_SESSION['usernamemsg'] = "A username is required.";

if($_POST['password'] != "") {
	$password = $_POST['password'];
}
else
	$_SESSION['passwordmsg'] = "A password is required.";


if($username === "" || $password === "") {
	header("Location: $referrer", true, 302);
	die();
}


$server = ['localhost', 'root', '', 'data'];
$conn = new mysqli($server[0], $server[1], $server[2], $server[3]);

$qry = $conn->prepare("SELECT * FROM users WHERE user_uid=? OR user_email=?;");
$qry->bind_param('ss', $username, $username);

if(!$qry->execute()) {
	switch($qry->errno) {
		default:
			$_SESSION['loginmsg'] = "Unknown error (" . $qry->errno . "): " . $qry->error;
			header("Location: $referrer", true, 302);
			die();
	}
}
$user = $qry->get_result();
$user = $user->fetch_array(MYSQLI_ASSOC) ?? FALSE;
if(!$user) {
	$_SESSION['loginmsg'] = "Your uname or pw is incorrect.";
} else {
	$pwcheck = password_verify($password, $user['user_password']);
	if($pwcheck) {
		$_SESSION['sessionid'] = $user['user_uid'];
		$_SESSION['loggedin'] = true;
		if($remember) {
			$_SESSION['remember'] = true;
			$params = session_get_cookie_params();
			setcookie(session_name(), $_COOKIE[session_name()], time() + 60 * 60 * 24 * 365 * 5, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
		}
	} else
		$_SESSION['loginmsg'] = "Your uname or pw is incorrect.";
}

header("Location: $referrer", true, 302);
?>