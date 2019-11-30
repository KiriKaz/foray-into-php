<?php include_once 'includes/session.inc.php'?>
<?php

if($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['action'])) {
	header("Location: $referrer", true, 302);
	die();
}

switch($_POST['action']) {
	case 'delete':
		$server = ['localhost', 'root', '', 'data'];
		$conn = new mysqli($server[0], $server[1], $server[2], $server[3]);

		if($conn->connect_error)
			die("Connection failed: ". $conn->connect_error);

		$qry = $conn->prepare("DELETE FROM comments WHERE comment_id = ?;");
		$qry->bind_param('s', $_POST['comment']);
		if(!$qry->execute())
			echo "Error: " . $qry->error;
		else
			echo "Comment deleted.";
		break;
	case 'create':
		$server = ['localhost', 'root', '', 'data'];
		$conn = new mysqli($server[0], $server[1], $server[2], $server[3]);

		$username = $_POST['username'] ?? "";
		$content = $_POST['content'] ?? "";
		$time = time();

		if($username === "" || $content === "") {
			echo "A comment is required. Can't be empty!";
			die();
		}

		$qry = $conn->prepare("INSERT INTO comments(user_uid, comment_content, comment_time) VALUES (?, ?, ?);");
		$qry->bind_param('sss', $username, $content, $time);
		if(!$qry->execute())
			echo $qry->error;
		// else
			// echo "Comment created.";
		break;
	case 'edit':
		$server = ['localhost', 'root', '', 'data'];
		$conn = new mysqli($server[0], $server[1], $server[2], $server[3]);

		$comment_text = $_POST['comment'];
		$comment_id = $_POST['commentId'];

		$qry = $conn->prepare("UPDATE comments SET comment_content = ? WHERE comment_id = ?;");
		$qry->bind_param('ss', $comment_text, $comment_id);
		if(!$qry->execute())
			echo "Error: " . $qry->error;
		else
			header("Location: comments.php", true, 302);
			die();
		break;
	default:
		header("Location: $referrer", true, 302);
		die();
}
?>