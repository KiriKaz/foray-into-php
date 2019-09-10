<?php include_once 'includes/session.inc.php';?>
<?php

$username = $_SESSION['sessionid'] ?? "Anonymous";

?>
<!DOCTYPE html>
<html lang="PT-BR">
	<head>
		<meta charset="UTF-8">
		<title>Marcos Bastida - Portfolio</title>
		<link rel="stylesheet" href="etc/styles.css">
		<link rel="stylesheet" href="etc/comment_colors.css">

		<!-- Bootstrap -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

		<!-- FontAwesome -->
		<script src="https://kit.fontawesome.com/590513b2d9.js"></script>
	</head>
	<body>
	<?php include_once 'header.php';?>
	<div id='commentsbox'>
		<div class='row justify-content-center'>
			<div class='col-auto'>
				<h4>Comments</h3>
			</div>
			<div class='col-8'>
				<div class='bluebreak'></div>
			</div>
		</div>
		<div id='comments'>
			<!-- <div class='comment'>
				<p class='username'>Administrator</p>
				<p class='date'><?=date('h:ma d/m/y')?></p>
				<p class='content'>Hi. This is a hard-coded-in comment. I am currently testing the overflow-x capabilities of the comment section so far. If this does not work properly, I will die.</p>
			</div> -->
			<?php
			$server = ['localhost', 'root', '', 'data'];
			$conn = new mysqli($server[0], $server[1], $server[2], $server[3]);

			$qry = $conn->prepare("SELECT * FROM comments ORDER BY comment_time DESC");
			$qry->execute();
			$result = $qry->get_result();
			while($comments = $result->fetch_array(MYSQLI_ASSOC)) {
				$time = date('h:ia d/m/y', $comments['comment_time']);
				$adminpanel = "";
				if($username == 'Administrator') {
					$adminpanel = "<a class='panelDelete' id='cid-{$comments['comment_id']}' href='#'' ><i class='fas fa-trash-alt delete'></i></a>";
				}
				$uname = $comments['user_uid'];
				echo <<<CMM
				<div class='comment'>
					<p class='username $uname'>$uname</p>
					<p class='date'>$time</p>
					<p class='content'>{$comments['comment_content']}</p>
					<div class='panel'>
						$adminpanel
					</div>
				</div>
				CMM;
			}
			$conn->close();
			?>
		</div>
 		<form id='postcomment' class='col-12'>
			<div class='form-row' id='commentcontent'>
				<div class='input-group'>
					<div class='input-group-prepend'>
						<div class='input-group-text'><?=$username?></div>
						<input type='hidden' name='username' value='<?=$username?>'>
					</div>
					<input type='text' class='form-control' id='commentContent' placeholder="Comment" name='content'>
					<input type='submit' class='btn btn-secondary' id='sendcomment' value='Send'>
				</div>
			</div>
		</form>
	</div>	
	<?php include_once 'footer.php';?>

	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>

	<!-- Bootstrap -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	<!-- Comment Admin Panel -->
	<script>
		$(document).ready(function(){
	        var ajaxurl = 'managecomment.php';
		    $('.panelDelete').click(function(){
		        var clickBtnValue = $(this).attr('id');
		        clickBtnValue = clickBtnValue.slice(4);
		        var data = {'action': 'delete', 'comment': clickBtnValue};
		        $.ajax({
		        	type: "POST",
		        	url: ajaxurl,
		        	data: data
		        }).done(function(response) {
		            alert(response);
		            location.reload();
		        });
		    });
		    $('#postcomment').submit(function(event){
		    	event.preventDefault();
		    	var inputgroup = $(this).children().children() 
		    	var username = inputgroup.children('.input-group-prepend').children('input').val();
		    	var content = inputgroup.children('#commentContent').val();
		    	var data = {'action': 'create', 'username': username, 'content': content};
		    	$.ajax({
		    		type: "POST",
		    		url: ajaxurl,
		    		data: data
		    	}).done(function(response) {
		    		// alert(response);
		    		location.reload();
		    	});
		    });
		})
	</script>
	</body>
</html>