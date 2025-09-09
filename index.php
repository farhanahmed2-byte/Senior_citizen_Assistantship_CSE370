<?php
$title = 'dashboard';
include __DIR__ . '/includes/header.php';
require_login();
?>
<div class="row mb-4">
	<div class="col-12">
		<div class="card bg-primary text-white">
			<div class="card-body">
				<h4 class="card-title"><?php echo t('welcome'); ?>, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>!</h4>
				<p class="card-text mb-0">
					<?php echo t('logged_in_as'); ?> <strong><?php echo ucfirst($_SESSION['user']['role']); ?></strong>
					<?php if (is_admin()): ?>
						<span class="badge bg-danger ms-2">ADMIN</span>
					<?php endif; ?>
				</p>
			</div>
		</div>
	</div>
</div>

<div class="row g-3">
	<div class="col-md-3">
		<a href="volunteers.php" class="text-decoration-none">
			<div class="card text-center p-4 h-100">
				<div class="card-body d-flex flex-column">
					<i class="fas fa-users fa-2x text-primary mb-2"></i>
					<strong><?php echo t('volunteers'); ?></strong>
					<small class="text-muted"><?php echo t('manage_volunteers'); ?></small>
				</div>
			</div>
		</a>
	</div>
	<div class="col-md-3">
		<a href="schedules.php" class="text-decoration-none">
			<div class="card text-center p-4 h-100">
				<div class="card-body d-flex flex-column">
					<i class="fas fa-calendar fa-2x text-success mb-2"></i>
					<strong><?php echo t('schedule_requests'); ?></strong>
					<small class="text-muted"><?php echo t('view_assistance_requests'); ?></small>
				</div>
			</div>
		</a>
	</div>
	<div class="col-md-3">
		<a href="alerts.php" class="text-decoration-none">
			<div class="card text-center p-4 h-100">
				<div class="card-body d-flex flex-column">
					<i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
					<strong><?php echo t('emergency_alerts'); ?></strong>
					<small class="text-muted"><?php echo t('emergency_notifications'); ?></small>
				</div>
			</div>
		</a>
	</div>
	<div class="col-md-3">
		<a href="feedback.php" class="text-decoration-none">
			<div class="card text-center p-4 h-100">
				<div class="card-body d-flex flex-column">
					<i class="fas fa-comments fa-2x text-info mb-2"></i>
					<strong><?php echo t('feedback'); ?></strong>
					<small class="text-muted"><?php echo t('share_thoughts'); ?></small>
				</div>
			</div>
		</a>
	</div>
	<div class="col-md-3">
		<a href="notifications.php" class="text-decoration-none">
			<div class="card text-center p-4 mt-3 h-100">
				<div class="card-body d-flex flex-column">
					<i class="fas fa-bell fa-2x text-warning mb-2"></i>
					<strong><?php echo t('notifications'); ?></strong>
					<small class="text-muted"><?php echo t('system_messages'); ?></small>
				</div>
			</div>
		</a>
	</div>
	<div class="col-md-3">
		<a href="volunteer_schedule.php" class="text-decoration-none">
			<div class="card text-center p-4 mt-3 h-100">
				<div class="card-body d-flex flex-column">
					<i class="fas fa-clock fa-2x text-secondary mb-2"></i>
					<strong><?php echo t('volunteer_schedule'); ?></strong>
					<small class="text-muted"><?php echo t('manage_schedules'); ?></small>
				</div>
			</div>
		</a>
	</div>
	<div class="col-md-3">
		<a href="donations.php" class="text-decoration-none">
			<div class="card text-center p-4 mt-3 h-100">
				<div class="card-body d-flex flex-column">
					<i class="fas fa-hand-holding-heart fa-2x text-success mb-2"></i>
					<strong><?php echo t('donations'); ?></strong>
					<small class="text-muted"><?php echo t('support_our_cause'); ?></small>
				</div>
			</div>
		</a>
	</div>
	<div class="col-md-3">
		<a href="locations.php" class="text-decoration-none">
			<div class="card text-center p-4 mt-3 h-100">
				<div class="card-body d-flex flex-column">
					<i class="fas fa-map-marker-alt fa-2x text-danger mb-2"></i>
					<strong><?php echo t('location'); ?></strong>
					<small class="text-muted"><?php echo t('location_tracking'); ?></small>
				</div>
			</div>
		</a>
	</div>
</div>

<!-- Quick Stats Section - Only visible to admin and volunteers -->
<?php if ($_SESSION['user']['role'] !== 'senior'): ?>
<div class="row mt-4">
	<div class="col-12">
		<h5 class="mb-3"><?php echo t('quick_overview'); ?></h5>
	</div>
	<?php
	$db = get_db_connection();
	
	// Get quick stats
	$volunteer_count = $db->query('SELECT COUNT(*) FROM volunteer')->fetchColumn();
	$senior_count = $db->query('SELECT COUNT(*) FROM senior')->fetchColumn();
	$pending_requests = $db->query('SELECT COUNT(*) FROM request WHERE status = "Pending"')->fetchColumn();
	$active_alerts = $db->query('SELECT COUNT(*) FROM emergency_alert WHERE alert_status = "Active"')->fetchColumn();
	?>
	<div class="col-md-3">
		<div class="card text-center">
			<div class="card-body">
				<h3 class="text-primary"><?php echo $volunteer_count; ?></h3>
				<p class="card-text"><?php echo t('active_volunteers'); ?></p>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card text-center">
			<div class="card-body">
				<h3 class="text-info"><?php echo $senior_count; ?></h3>
				<p class="card-text"><?php echo t('registered_seniors'); ?></p>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card text-center">
			<div class="card-body">
				<h3 class="text-warning"><?php echo $pending_requests; ?></h3>
				<p class="card-text"><?php echo t('pending_requests'); ?></p>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card text-center">
			<div class="card-body">
				<h3 class="text-danger"><?php echo $active_alerts; ?></h3>
				<p class="card-text"><?php echo t('active_alerts'); ?></p>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>

