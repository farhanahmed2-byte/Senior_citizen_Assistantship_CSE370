<?php
$title = 'Emergency Alerts';
include __DIR__ . '/includes/header.php';
require_login();
ensure_csrf();
$db = get_db_connection();

if (is_post()) {
	$action = $_POST['action'] ?? '';
	if ($action === 'create') {
		$stmt = $db->prepare('INSERT INTO emergency_alert (senior_id, location, date, time, alert_status) VALUES (?,?,?,?,?)');
		$stmt->execute([
			(int)($_POST['senior_id'] ?? 1), // Default to first senior for now
			trim($_POST['location'] ?? ''),
			$_POST['date'] ?? date('Y-m-d'),
			$_POST['time'] ?? date('H:i:s'),
			'Active'
		]);
	} elseif ($action === 'respond' && (is_volunteer() || is_admin())) {
		$id = (int)($_POST['id'] ?? 0);
		$volunteer_id = $_SESSION['user']['id'];
		
		// Check if alert exists and is still active
		$stmt = $db->prepare('SELECT alert_status FROM emergency_alert WHERE ID = ?');
		$stmt->execute([$id]);
		$alert = $stmt->fetch();
		
		if ($alert && $alert['alert_status'] === 'Active') {
			// Update alert status to "Responded"
			$stmt = $db->prepare('UPDATE emergency_alert SET alert_status="Responded" WHERE ID=?');
			$stmt->execute([$id]);
			
			// Set success message
			$_SESSION['success_message'] = 'You have successfully responded to the emergency alert!';
			// Redirect to prevent form resubmission
			header('Location: ' . $_SERVER['PHP_SELF']);
			exit;
		} else {
			// Set error message
			$_SESSION['error_message'] = 'This alert is no longer active or does not exist.';
		}
	} elseif ($action === 'resolve' && is_admin()) {
		$id = (int)($_POST['id'] ?? 0);
		$db->prepare('UPDATE emergency_alert SET alert_status="Resolved" WHERE ID=?')->execute([$id]);
		$_SESSION['success_message'] = 'Emergency alert has been resolved successfully!';
		// Redirect to prevent form resubmission
		header('Location: ' . $_SERVER['PHP_SELF']);
		exit;
	} elseif ($action === 'delete' && is_admin()) {
		$id = (int)($_POST['id'] ?? 0);
		$db->prepare('DELETE FROM emergency_alert WHERE ID=?')->execute([$id]);
	}
}

// Get alerts with senior information
$rows = $db->query('
	SELECT ea.*, 
		   u.name as senior_name
	FROM emergency_alert ea 
	LEFT JOIN senior s ON ea.senior_id = s.ID 
	LEFT JOIN user u ON s.u1_id = u.Id 
	ORDER BY ea.ID DESC
')->fetchAll();
?>

<!-- Display success/error messages -->
<?php if (isset($_SESSION['success_message'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
	<i class="fas fa-check-circle me-2"></i>
	<?php echo htmlspecialchars($_SESSION['success_message']); ?>
	<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
	<i class="fas fa-exclamation-triangle me-2"></i>
	<?php echo htmlspecialchars($_SESSION['error_message']); ?>
	<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
	<h3><?php echo t('emergency_alerts'); ?></h3>
	<button class="btn btn-danger" data-bs-toggle="collapse" data-bs-target="#createForm"><?php echo t('create'); ?></button>
</div>

<div id="createForm" class="collapse mb-4">
	<div class="card"><div class="card-body">
		<form method="post">
			<?php echo csrf_field(); ?>
			<input type="hidden" name="action" value="create">
			<div class="row g-2">
				<div class="col-md-3">
					<select name="senior_id" class="form-control" required>
						<option value=""><?php echo t('select_senior'); ?></option>
						<?php
						$seniors = $db->query('SELECT s.ID, u.name FROM senior s JOIN user u ON s.u1_id = u.Id')->fetchAll();
						foreach ($seniors as $senior): ?>
							<option value="<?php echo $senior['ID']; ?>"><?php echo htmlspecialchars($senior['name']); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-3"><input name="location" class="form-control" placeholder="Location" required></div>
				<div class="col-md-3"><input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>"></div>
				<div class="col-md-3"><input type="time" name="time" class="form-control" value="<?php echo date('H:i:s'); ?>"></div>
			</div>
			<div class="mt-3"><button class="btn btn-success"><?php echo t('save'); ?></button></div>
		</form>
	</div></div>
</div>

<div class="table-responsive">
	<table class="table table-striped">
		<thead><tr><th>ID</th><th>Senior</th><th>Location</th><th>Date</th><th>Time</th><th>Status</th><th><?php echo t('action'); ?></th></tr></thead>
		<tbody>
			<?php foreach ($rows as $r): ?>
			<tr>
				<td><?php echo (int)$r['ID']; ?></td>
				<td><?php echo htmlspecialchars($r['senior_name'] ?? t('unknown')); ?></td>
				<td><?php echo htmlspecialchars($r['location']); ?></td>
				<td><?php echo htmlspecialchars($r['date']); ?></td>
				<td><?php echo htmlspecialchars($r['time']); ?></td>
				<td>
					<span class="badge bg-<?php 
						echo $r['alert_status'] === 'Active' ? 'danger' : 
							($r['alert_status'] === 'Responded' ? 'warning' : 'success'); 
					?>">
						<?php echo htmlspecialchars($r['alert_status']); ?>
					</span>
				</td>
				<td>
					<div class="d-flex gap-1">
						<?php if ($r['alert_status'] !== 'Resolved'): ?>
							<?php if (is_volunteer() || is_admin()): ?>
							<!-- Volunteers and admins can respond to alerts -->
							<form method="post" class="d-inline">
								<?php echo csrf_field(); ?>
								<input type="hidden" name="action" value="respond">
								<input type="hidden" name="id" value="<?php echo (int)$r['ID']; ?>">
								<button class="btn btn-sm btn-warning"><?php echo t('respond'); ?></button>
							</form>
							<?php endif; ?>
							<?php if (is_admin()): ?>
							<!-- Only admins can resolve alerts -->
							<form method="post" class="d-inline">
								<?php echo csrf_field(); ?>
								<input type="hidden" name="action" value="resolve">
								<input type="hidden" name="id" value="<?php echo (int)$r['ID']; ?>">
								<button class="btn btn-sm btn-outline-success"><?php echo t('resolve'); ?></button>
							</form>
							<?php endif; ?>
						<?php endif; ?>
						<?php if (is_admin()): ?>
						<form method="post" class="d-inline">
							<?php echo csrf_field(); ?>
							<input type="hidden" name="action" value="delete">
							<input type="hidden" name="id" value="<?php echo (int)$r['ID']; ?>">
							<button class="btn btn-sm btn-danger" onclick="return confirm('<?php echo t('are_you_sure_delete'); ?>')"><?php echo t('delete'); ?></button>
						</form>
						<?php endif; ?>
					</div>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>


<?php include __DIR__ . '/includes/footer.php'; ?>

