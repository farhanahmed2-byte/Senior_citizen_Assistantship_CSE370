<?php
$title = 'Schedule Requests';
include __DIR__ . '/includes/header.php';
require_login();
ensure_csrf();
$db = get_db_connection();

if (is_post()) {
	$action = $_POST['action'] ?? '';
	if ($action === 'create') {
		// Determine senior_id based on user role
		$senior_id = 1; // Default fallback
		
		if (is_senior()) {
			// For seniors, get their own senior record ID
			$stmt = $db->prepare('SELECT ID FROM senior WHERE u1_id = ?');
			$stmt->execute([$_SESSION['user']['id']]);
			$senior = $stmt->fetch();
			if ($senior) {
				$senior_id = $senior['ID'];
			}
		} else {
			// For admins/volunteers, use the selected senior_id
			$senior_id = (int)($_POST['senior_id'] ?? 1);
		}
		
		$stmt = $db->prepare('INSERT INTO request (date, type_assistance, s_id, status) VALUES (?,?,?,?)');
		$stmt->execute([
			$_POST['request_date'] ?? date('Y-m-d'),
			trim($_POST['type_assistance'] ?? ''),
			$senior_id,
			'Pending'
		]);
		
		// Set success message
		$_SESSION['request_success'] = 'Your assistance request has been submitted successfully!';
		
		// Redirect to prevent form resubmission
		header('Location: ' . $_SERVER['PHP_SELF']);
		exit;
	} elseif ($action === 'status') {
		$id = (int)($_POST['id'] ?? 0);
		$status = $_POST['status'] ?? 'Pending';
		$db->prepare('UPDATE request SET status=? WHERE req_id=?')->execute([$status, $id]);
	} elseif ($action === 'delete' && is_admin()) {
		$id = (int)($_POST['id'] ?? 0);
		$db->prepare('DELETE FROM request WHERE req_id=?')->execute([$id]);
	}
}

// Get requests with senior information
if (is_senior()) {
	// For seniors, only show their own requests
	$stmt = $db->prepare('
		SELECT r.*, s.pref_language, u.name as senior_name 
		FROM request r 
		LEFT JOIN senior s ON r.s_id = s.ID 
		LEFT JOIN user u ON s.u1_id = u.Id 
		WHERE s.u1_id = ? 
		ORDER BY r.req_id DESC
	');
	$stmt->execute([$_SESSION['user']['id']]);
	$rows = $stmt->fetchAll();
} else {
	// For admins and volunteers, show all requests
	$rows = $db->query('SELECT r.*, s.pref_language, u.name as senior_name FROM request r LEFT JOIN senior s ON r.s_id = s.ID LEFT JOIN user u ON s.u1_id = u.Id ORDER BY r.req_id DESC')->fetchAll();
}
?>
<div class="d-flex justify-content-between align-items-center mb-3">
	<h3><?php echo t('schedule_requests'); ?></h3>
	<button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#createForm">
		<i class="fas fa-plus me-2"></i><?php echo t('create'); ?>
	</button>
</div>

<?php if (isset($_SESSION['request_success'])): ?>
<div class="alert alert-success alert-dismissible fade show mb-4">
	<i class="fas fa-check-circle me-2"></i>
	<strong>Success!</strong> <?php echo htmlspecialchars($_SESSION['request_success']); ?>
	<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['request_success']); endif; ?>

<?php if (is_senior()): ?>
<div class="alert alert-info mb-4">
	<i class="fas fa-info-circle me-2"></i>
	<strong>Welcome!</strong> You can now create your own assistance requests. 
	Simply fill out the form below to request help from our volunteers.
</div>
<?php endif; ?>

<div id="createForm" class="collapse mb-4">
	<div class="card"><div class="card-body">
		<form method="post">
			<?php echo csrf_field(); ?>
			<input type="hidden" name="action" value="create">
			<div class="row g-2">
				<div class="col-md-3">
					<label class="form-label">Type of Assistance</label>
					<input name="type_assistance" class="form-control" placeholder="e.g., Medical checkup, Grocery shopping, Transportation" required>
				</div>
				<div class="col-md-3">
					<label class="form-label">Request Date</label>
					<input type="date" name="request_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
				</div>
				<?php if (!is_senior()): ?>
				<div class="col-md-3">
					<label class="form-label">Select Senior</label>
					<select name="senior_id" class="form-control" required>
						<option value="">Select Senior</option>
						<?php
						$seniors = $db->query('SELECT s.ID, u.name FROM senior s JOIN user u ON s.u1_id = u.Id')->fetchAll();
						foreach ($seniors as $senior): ?>
							<option value="<?php echo $senior['ID']; ?>"><?php echo htmlspecialchars($senior['name']); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php else: ?>
				<div class="col-md-3">
					<label class="form-label">Requesting For</label>
					<input type="text" class="form-control" value="Yourself" readonly>
					<small class="text-muted">You are creating this request for yourself</small>
				</div>
				<?php endif; ?>
			</div>
			<div class="mt-3">
				<button class="btn btn-success">
					<i class="fas fa-plus me-2"></i><?php echo t('create_request'); ?>
				</button>
			</div>
		</form>
	</div></div>
</div>

<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>ID</th>
				<?php if (!is_senior()): ?><th>Senior</th><?php endif; ?>
				<th>Type of Assistance</th>
				<th>Date</th>
				<th><?php echo t('status'); ?></th>
				<th><?php echo t('action'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $r): ?>
			<tr>
				<td><?php echo (int)$r['req_id']; ?></td>
				<?php if (!is_senior()): ?>
				<td><?php echo htmlspecialchars($r['senior_name'] ?? 'Unknown'); ?></td>
				<?php endif; ?>
				<td><?php echo htmlspecialchars($r['type_assistance']); ?></td>
				<td><?php echo htmlspecialchars($r['date']); ?></td>
				<td>
					<?php if (is_senior()): ?>
						<!-- Seniors can only view status, not change it -->
						<span class="badge bg-<?php 
							echo $r['status'] === 'Pending' ? 'warning' : 
								($r['status'] === 'Approved' ? 'success' : 
								($r['status'] === 'Rejected' ? 'danger' : 'info')); 
						?>">
							<?php echo t(strtolower($r['status'])); ?>
						</span>
					<?php else: ?>
						<!-- Admin and volunteers can change status -->
						<form method="post" class="d-inline">
							<?php echo csrf_field(); ?>
							<input type="hidden" name="action" value="status">
							<input type="hidden" name="id" value="<?php echo (int)$r['req_id']; ?>">
							<select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
								<option value="Pending" <?php echo $r['status']==='Pending'?'selected':''; ?>><?php echo t('pending'); ?></option>
								<option value="Approved" <?php echo $r['status']==='Approved'?'selected':''; ?>><?php echo t('approved'); ?></option>
								<option value="Rejected" <?php echo $r['status']==='Rejected'?'selected':''; ?>><?php echo t('rejected'); ?></option>
								<option value="Completed" <?php echo $r['status']==='Completed'?'selected':''; ?>><?php echo t('completed'); ?></option>
							</select>
						</form>
					<?php endif; ?>
				</td>
				<td>
					<?php if (is_admin()): ?>
					<form method="post" class="d-inline">
						<?php echo csrf_field(); ?>
						<input type="hidden" name="action" value="delete">
						<input type="hidden" name="id" value="<?php echo (int)$r['req_id']; ?>">
						<button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this request?')"><?php echo t('delete'); ?></button>
					</form>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

