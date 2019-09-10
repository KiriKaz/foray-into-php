<?php
if(basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])){
	header('Location: ..', true, 302);
	die();
}

$year = date("Y");

$copystring = "<p>Copyright &copy; 2019-$year Marcos Bastida</p>";

if($year === '2019') {
	$copystring = "<p>Copyright &copy; 2019 Marcos Bastida</p>";
}

echo <<<FOOT
<footer>
	$copystring
</footer>\n
FOOT;
?>