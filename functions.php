<?php

function redirect(string $path): void {
	header('Location: ' . $path);
	exit;
}

function csrf_token(): string {
	if (empty($_SESSION['csrf'])) {
		$_SESSION['csrf'] = bin2hex(random_bytes(16));
	}
	return $_SESSION['csrf'];
}

function csrf_field(): string {
	$token = csrf_token();
	return '<input type="hidden" name="csrf" value="' . htmlspecialchars($token) . '">';
}

function ensure_csrf(): void {
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (empty($_POST['csrf']) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'])) {
			http_response_code(400);
			echo 'Invalid CSRF token';
			exit;
		}
	}
}

function is_post(): bool {
	return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function require_login(): void {
	if (empty($_SESSION['user'])) {
		redirect('/login.php');
	}
}

function is_admin(): bool {
	return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
}

function is_volunteer(): bool {
	return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'volunteer';
}

function is_senior(): bool {
	return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'senior';
}

function require_admin(): void {
	require_login();
	if (!is_admin()) {
		http_response_code(403);
		echo 'Access denied. Admin privileges required.';
		exit;
	}
}

function require_volunteer_or_admin(): void {
	require_login();
	if (!is_volunteer() && !is_admin()) {
		http_response_code(403);
		echo 'Access denied. Volunteer or Admin privileges required.';
		exit;
	}
}

function require_senior_or_admin(): void {
	require_login();
	if (!is_senior() && !is_admin()) {
		http_response_code(403);
		echo 'Access denied. Senior or Admin privileges required.';
		exit;
	}
}

?>

