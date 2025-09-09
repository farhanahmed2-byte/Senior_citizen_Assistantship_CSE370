<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Basic error visibility during development; comment out in production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/i18n.php';
require_once __DIR__ . '/functions.php';

$title = $title ?? t('app_title');
?>
<!DOCTYPE html>
<html lang="<?php echo current_lang(); ?>">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo htmlspecialchars($title); ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>
<main class="container py-4">

