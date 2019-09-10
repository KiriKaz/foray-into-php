<?php include_once 'includes/session.inc.php'?>
<?php
// Register!

if($_SERVER['REQUEST_METHOD'] != "POST") {
	header("Location: $referrer", true, 302);
	die();
}

$username = $email = $password = "";


if($_POST['username'] != "")
	$username = $_POST['username'];
else
	$_SESSION['regusernamemsg'] = "A username is required.";

preg_match("/^([\w\-\.]+)@((\[([0-9]{1,3}\.){3}[0-9]{1,3}\])|(([\w\-]+\.)+)([a-zA-Z]{2,9}))$/", $_POST['email'], $emailmatch);
if($emailmatch != [])  // Email found, good to go.
	$email = $emailmatch[0];
elseif($_POST['email'] == "")  // No email matched? Is the email box empty?
	$_SESSION['regemailmsg'] = "An email is required.";
else  // The user did type _something_, just not a valid email.
	$_SESSION['regemailmsg'] = "Email is invalid.";

if($_POST['password'] != "" && $_POST['password2'] != "")
	if($_POST['password'] != $_POST['password2']) {
		$_session['regpasswordmsg'] = "Passwords don't match!";
	} else {
		$password = $_POST['password'];
	}
else
	$_SESSION['regpasswordmsg'] = "A password is required.";


if($username == "" || $emailmatch == [] || $password == "") {
	header("Location: $referrer", true, 302);
	die();
}

$server = ['localhost', 'root', '', 'data'];
$conn = new mysqli($server[0], $server[1], $server[2], $server[3]);

if($conn->connect_error)
	die("Connection failed: ". $conn->connect_error);

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$register = $conn->prepare("INSERT INTO users(user_uid, user_email, user_password) VALUES (?, ?, ?);");
$register->bind_param("sss", $username, $email, $password_hash);

if(!$register->execute()) {
	switch($register->errno) {
		case 1062:
			$_SESSION['registermsg'] = "Username already in use";
			break;
		default:
			$_SESSION['registermsg'] = "Unknown error: (" . $register->errno . ") " . htmlspecialchars($register->error);
	}
} else
	$_SESSION['loginmsg'] = "Registered successfully!";

// if($result)
// 	$_SESSION['registermsg'] = "Registered successfully!";
// else {
// 	$username_error = "Duplicate entry '$username' for key 'PRIMARY'";
// 	switch(mysqli_error($conn)) {
// 		case($username_error):
// 			$_SESSION['registermsg'] = "User already exists";
// 			break;
// 		default:
// 			$_SESSION['registermsg'] = "Unknown error: " . mysqli_error($conn);
// 	}
// }

header("Location: $referrer", true, 302);
?>