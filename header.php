<?php
$homeactive = $commentsactive = "";
switch(basename($_SERVER["SCRIPT_FILENAME"])) {
	case 'index.php':
		$homeactive = " active";
		break;
	case 'comments.php':
		$commentsactive = " active";
		break;
	case 'header.php':
		header('Location: ..', true, 302);
		die();
	default:
}

$loginbutton = $registerbutton = $extrabutton = "";  // TODO: Extrabutton for managing users in the db

$usernamemsg = $passwordmsg = $loginmsg = "";  // Initiate messages for errors on login
$regusernamemsg = $regemailmsg = $regpasswordmsg = $registermsg = "";  // Initiate messages for errors on register

$usernamevalid = $passwordvalid = "";  // Initiate valid/invalid status
$regusernamevalid = $regpasswordvalid = $regemailvalid = "";


if(isset($_SESSION['usernamemsg'])) {
	$msg = $_SESSION['usernamemsg'];
	unset($_SESSION['usernamemsg']);
	$usernamemsg = "<div class='invalid-feedback'>$msg</div>";
	$usernamevalid = " is-invalid";
}

if(isset($_SESSION['passwordmsg'])) {
	$msg = $_SESSION['passwordmsg'];
	unset($_SESSION['passwordmsg']);
	$passwordmsg = "<div class='invalid-feedback'>$msg</div>";
	$passwordvalid = " is-invalid";
}

if(isset($_SESSION['loginmsg'])) {
	$msg = $_SESSION['loginmsg'];
	unset($_SESSION['loginmsg']);
	$loginmsg = "<span class='text-danger'>* $msg</span>";
}

$logmsg = [$usernamemsg, $passwordmsg, $loginmsg];

if(isset($_SESSION['regusernamemsg'])) {
	$msg = $_SESSION['regusernamemsg'];
	unset($_SESSION['regusernamemsg']);
	$regusernamemsg = "<div class='invalid-feedback'>$msg</div>";
	$regusernamevalid = " is-invalid";
}

if(isset($_SESSION['regemailmsg'])) {
	$msg = $_SESSION['regemailmsg'];
	unset($_SESSION['regemailmsg']);
	$regemailmsg = "<div class='invalid-feedback'>$msg</div>";
	$regemailvalid = " is-invalid";
}

if(isset($_SESSION['regpasswordmsg'])) {
	$msg = $_SESSION['regpasswordmsg'];
	unset($_SESSION['regpasswordmsg']);
	$regpasswordmsg = "<div class='invalid-feedback'>$msg</div>";
	$regpasswordvalid = " is-invalid";
}

if(isset($_SESSION['registermsg'])) {
	$msg = $_SESSION['registermsg'];
	unset($_SESSION['registermsg']);
	$registermsg = "<span class='text-danger'>* $msg</span>";
}

$regmsg = [$regusernamemsg, $regemailmsg, $regpasswordmsg, $registermsg];

$logdropdown = $regdropdown = "dropdown";
$logdropdownmenu = $regdropdownmenu = "dropdown-menu";

if($logmsg != ["", "", ""]) {
	$logdropdown = "dropdown show";
	$logdropdownmenu = "dropdown-menu show";
} elseif($regmsg != ["", "", "", ""]) {
	$regdropdown = "dropdown show";
	$regdropdownmenu = "dropdown-menu show";
}

$remember = "";
if($_SESSION['remember'] ?? false){
	$remember = " ticked";
}


// Set up login and register buttons.
if(!isset($_SESSION['sessionid'])) {
	// If the user is not logged in, we'll display a login button:
	// 
	$usernamePlaceholders = ["enterSandman", "TheLegend27", "Aria", "O5-13", "Klef", "KickDickerson197@yahoomail.com", "MrOrph", "Nina", "RossmannGroup", "JorySr@enoof.org", "QuentinTrembley"];
	$index = array_rand($usernamePlaceholders);
	$placeholder = $usernamePlaceholders[$index];

	$loginbutton = <<<LGB
	<div class='nav-item $logdropdown'>
		<button class='nav-item btn btn-warning dropdown-toggle' type='button' id='loginbutton' data-toggle='dropdown'>
			Login
		</button>
		<div class='$logdropdownmenu' id='logindropdown'>
			<form action='login.php' method='post'>
				<div class='form-group'>
					<label for='usernameInput'>Username/email</label>
					<input type='text' class='form-control$usernamevalid' id='usernameInput' name='username' placeholder='$placeholder' maxlength='35'>
					$logmsg[0]
				</div>
				<div class='form-group'>
					<label for='passwordInput'>Password</label>
					<input type='password' class='form-control$passwordvalid' id='passwordInput' name='password' placeholder='Password' maxlength='255'>
					$logmsg[1]
				</div>
				<div class='form-group form-check'>
					<input type='checkbox' class='form-check-input' name='remember' id='rememberLogin'$remember>
					<label class='form-check-label' for='rememberLogin'>Remember me</label>
				</div>
				<button type='submit' class='btn btn-primary'>Login</button>$logmsg[2]
			</form>
		</div>
	</div>
	LGB;

	// 
	// And also a register button:
	// 

	$registerbutton = <<<RGB
	<div class='nav-item $regdropdown'>
		<button class='nav-item btn btn-warning dropdown-toggle' type='button' id='registerbutton' data-toggle='dropdown'>
			Register
		</button>
		<div class='$regdropdownmenu' id='registerdropdown'>
			<form action='register.php' method='post'>
				<div class='form-group'>
					<label for='usernameRInput'>Username</label>
					<input type='text' class='form-control$regusernamevalid' id='usernameRInput' name='username' maxlength='35'>
					$regmsg[0]
				</div>
				<div class='form-group'>
					<label for='emailRInput'>Email</label>
					<input type='email' class='form-control$regemailvalid' id='emailRInput' name='email' maxlength='255'>
					$regmsg[1]
				</div>
				<div class='form-group'>
					<label for='passwordRInput'>Password</label>
					<input type='password' class='form-control$regpasswordvalid' id='passwordRInput' name='password' maxlength='255'>
					$regmsg[2]
				</div>
				<div class='form-group'>
					<label for='password2RInput'>Confirm Password</label>
					<input type='password' class='form-control$regpasswordvalid' id='password2RInput' name='password2' maxlength='255'>
					$regmsg[2]
				</div>
				<button type='submit' class='btn btn-primary'>Register</button>$regmsg[3]
			</form>
		</div>
	</div>
	RGB;
} else {
	// Insert query for username from session/login id here.
	$username = $_SESSION['sessionid'];
	$loginbutton = "<a class='nav-item nav-link btn-warning' id='logoutbutton' href='destroy_session.php'>Logout ($username)</a>";
}


echo <<<HEADER
<header>
	<nav class='nav nav-pills'>
		<a class='nav-item nav-link$homeactive' id='homebutton' href='index.php'>Home</a>
		<a class='nav-item nav-link$commentsactive' id='commentsbutton' href='comments.php'>Comments</a>
		$loginbutton
		$registerbutton
		$extrabutton
	</nav>
</header>\n
HEADER;
?>