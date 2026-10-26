<?php if (session_status() === PHP_SESSION_NONE) session_start();require 'includes/functions.php';check_post();
$_SESSION=[];session_destroy();
header('Location: /');exit;
