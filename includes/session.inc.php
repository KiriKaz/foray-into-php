<?php
if(session_status() == PHP_SESSION_NONE) session_start();

$headers = getallheaders();
$referrer = $headers['Referer'] ?? "..";

?>