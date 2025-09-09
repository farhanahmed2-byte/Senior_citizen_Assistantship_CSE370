<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$lang = $_POST['lang'] ?? 'en';
	$_SESSION['lang'] = in_array($lang, ['en','es','bn','ja','hi']) ? $lang : 'en';
}
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/index.php'));
exit;

