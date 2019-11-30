<?php
$server = ['localhost', 'root', '', 'data'];
$conn = new mysqli($server[0], $server[1], $server[2], $server[3]);
date_default_timezone_set('America/Sao_Paulo');  // Com horário de verão. Claro.

$qry = $conn->prepare("SELECT * FROM comments WHERE comment_id = ?");
$qry->bind_param('s', $_POST['commentid']);
$qry->execute();
$result = $qry->get_result();
$comment_content = "";
while($comments = $result->fetch_array(MYSQLI_ASSOC)) {
	$comment_content = $comments['comment_content'];
	$echo = "afsdhgiuashdfga";
}
?>
<!DOCTYPE html>
<html lang="PT-BR">
	<head>
		<meta charset="UTF-8">
		<title>Atualizar Comentário</title>
		<link rel="stylesheet" href="etc/styles.css">

		<!-- Bootstrap -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</head>
	<body>
		<div id='registerdropdown'>
			<form action='managecomment.php' method='post'>
				<div class='form-group'>
					<textarea class='form-control' id='commentInput' name='comment' maxlength='2500'><?=$comment_content?></textarea>
					<input type="hidden" name="commentId" value="<?=$_POST['commentid']?>">
					<input type="hidden" name="action" value="edit">
				</div>
				<button type='submit' class='btn btn-primary'>Update</button>
			</form>
		</div>
	</body>
</html>