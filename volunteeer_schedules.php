<?php
$title = 'Volunteer Schedule';
include __DIR__ . '/includes/header.php';
require_login();
ensure_csrf();
$db = get_db_connection();

// Get volunteers with user information
$volunteers = $db->query('SELECT v.ID, u.name FROM volunteer v JOIN user u ON v.U_id = u.Id ORDER BY u.name')->fetchAll();

if (is_post()) {
	$action = $_POST['action'] ?? '';
	if ($action === 'create' && (is_volunteer() || is_admin())) {
		$stmt = $db->prepare('INSERT INTO schedule (name, date, v_id, request_id) VALUES (?,?,?,?)');
		$stmt->execute([
			trim($_POST['name'] ?? 'Volunteer Shift'),
			$_POST['shift_date'],
			(int)$_POST['volunteer_id'],
			null // No request ID for now
		]);
	} elseif ($action === 'delete' && is_admin()) {
		$id = (int)($_POST['id'] ?? 0);
		$db->prepare('DELETE FROM schedule WHERE ID=?')->execute([$id]);
	}
}

// Get schedules with volunteer information
$rows = $db->query('SELECT s.*, u.name as volunteer_name FROM schedule s JOIN volunteer v ON s.v_id = v.ID JOIN user u ON v.U_id = u.Id ORDER BY s.date DESC, s.ID DESC')->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
	<h3><?php echo t('volunteer_schedule'); ?></h3>
	<?php if (is_volunteer() || is_admin()): ?>
	<button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#createForm"><?php echo t('create'); ?></button>
	<?php endif; ?>
</div>

<?php if (is_senior()): ?>
<div class="alert alert-info mb-4">
	<i class="fas fa-info-circle me-2"></i>
	<strong>Note:</strong> Only volunteers and administrators can create volunteer schedules. 
	You can view the schedule information here but cannot create or modify volunteer schedules.
</div>
<?php endif; ?>

<?php if (is_volunteer() || is_admin()): ?>
<div id="createForm" class="collapse mb-4">
	<div class="card"><div class="card-body">
		<form method="post">
			<?php echo csrf_field(); ?>
			<input type="hidden" name="action" value="create">
			<div class="row g-2">
				<div class="col-md-3">
					<select name="volunteer_id" class="form-select" required>
						<option value="">Select Volunteer</option>
						<?php foreach ($volunteers as $v): ?>
						<option value="<?php echo (int)$v['ID']; ?>"><?php echo htmlspecialchars($v['name']); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-3"><input type="date" name="shift_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required></div>
				<div class="col-md-3"><input name="name" class="form-control" placeholder="Shift Name" value="Volunteer Shift" required></div>
			</div>
			<div class="mt-3"><button class="btn btn-success"><?php echo t('save'); ?></button></div>
		</form>
	</div></div>
</div>
<?php endif; ?>

<div class="table-responsive">
	<table class="table table-striped">
		<thead><tr><th>ID</th><th>Volunteer</th><th>Shift Name</th><th>Date</th><th><?php echo t('action'); ?></th></tr></thead>
		<tbody>
			<?php foreach ($rows as $r): ?>
			<tr>
				<td><?php echo (int)$r['ID']; ?></td>
				<td><?php echo htmlspecialchars($r['volunteer_name']); ?></td>
				<td><?php echo htmlspecialchars($r['name']); ?></td>
				<td><?php echo htmlspecialchars($r['date']); ?></td>
				<td>
					<form method="post" class="d-inline">
						<?php echo csrf_field(); ?>
						<input type="hidden" name="action" value="delete">
						<input type="hidden" name="id" value="<?php echo (int)$r['ID']; ?>">
						<button class="btn btn-sm btn-danger"><?php echo t('delete'); ?></button>
					</form>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

