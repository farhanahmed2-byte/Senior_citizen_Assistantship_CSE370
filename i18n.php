<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

function current_lang(): string {
	return $_SESSION['lang'] ?? 'en';
}

function t(string $key): string {
	$lang = current_lang();
	$file = __DIR__ . '/../lang/' . $lang . '.php';
	$strings = [];
	if (file_exists($file)) {
		$strings = include $file;
	}
	return $strings[$key] ?? $key;
}

?>

