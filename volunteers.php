<?php
$title = 'Volunteers';
include __DIR__ . '/includes/header.php';
require_login();
ensure_csrf();

$db = get_db_connection();

if (is_post()) {
	$action = $_POST['action'] ?? '';
	if ($action === 'create' && is_admin()) {
		// Only admins can create volunteers through the website
		// First create user record
		$stmt = $db->prepare('INSERT INTO user (name, `phone no`, password) VALUES (?, ?, ?)');
		$stmt->execute([
			trim($_POST['name'] ?? ''),
			trim($_POST['phone'] ?? ''),
			'volunteer123', // Default password
		]);
		$user_id = $db->lastInsertId();
		
		// Then create volunteer record
		$stmt = $db->prepare('INSERT INTO volunteer (availability, Max_distance, skills, U_id) VALUES (?, ?, ?, ?)');
		$stmt->execute([
			trim($_POST['availability'] ?? 'Available'),
			(int)($_POST['max_distance'] ?? 10),
			trim($_POST['skills'] ?? ''),
			$user_id,
		]);
	} elseif ($action === 'delete' && is_admin()) {
		$id = (int)($_POST['id'] ?? 0);
		// Get user ID first
		$stmt = $db->prepare('SELECT U_id FROM volunteer WHERE ID = ?');
		$stmt->execute([$id]);
		$volunteer = $stmt->fetch();
		if ($volunteer) {
			// Delete volunteer record
			$db->prepare('DELETE FROM volunteer WHERE ID = ?')->execute([$id]);
			// Delete user record
			$db->prepare('DELETE FROM user WHERE Id = ?')->execute([$volunteer['U_id']]);
		}
	} elseif ($action === 'edit' && is_admin()) {
		// Only admins can edit volunteer details
		$id = (int)($_POST['id'] ?? 0);
		// Update volunteer record
		$stmt = $db->prepare('UPDATE volunteer SET availability=?, Max_distance=?, skills=? WHERE ID=?');
		$stmt->execute([
			trim($_POST['availability'] ?? ''),
			(int)($_POST['max_distance'] ?? 10),
			trim($_POST['skills'] ?? ''),
			$id,
		]);
		
		// Update user record
		$stmt = $db->prepare('UPDATE user SET name=?, `phone no`=? WHERE Id=(SELECT U_id FROM volunteer WHERE ID=?)');
		$stmt->execute([
			trim($_POST['name'] ?? ''),
			trim($_POST['phone'] ?? ''),
			$id,
		]);
	}
}

// Get volunteers with user information
$vols = $db->query('SELECT v.*, u.name, u.`phone no` FROM volunteer v JOIN user u ON v.U_id = u.Id ORDER BY v.ID DESC')->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
	<h3><?php echo t('volunteers'); ?></h3>
	<?php if (is_admin()): ?>
	<button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#createForm"><?php echo t('create'); ?></button>
	<?php endif; ?>
</div>

<?php if (is_senior()): ?>
<div class="alert alert-info mb-4">
	<i class="fas fa-info-circle me-2"></i>
	<strong>Note:</strong> Volunteers can only be created through the registration process or by administrators. 
	You can view volunteer information here but cannot create or modify volunteer details.
</div>
<?php endif; ?>

<?php if (is_admin()): ?>
<div id="createForm" class="collapse mb-4">
	<div class="card"><div class="card-body">
		<form method="post">
			<?php echo csrf_field(); ?>
			<input type="hidden" name="action" value="create">
			<div class="row g-2">
				<div class="col-md-3"><input name="name" class="form-control" placeholder="<?php echo t('name'); ?>" required></div>
				<div class="col-md-3"><input name="phone" class="form-control" placeholder="Phone" required></div>
				<div class="col-md-2"><input name="availability" class="form-control" placeholder="Availability" value="Available"></div>
				<div class="col-md-2"><input name="max_distance" class="form-control" placeholder="Max Distance (km)" value="10"></div>
				<div class="col-md-2"><input name="skills" class="form-control" placeholder="Skills"></div>
			</div>
			<div class="mt-3"><button class="btn btn-success"><?php echo t('save'); ?></button></div>
		</form>
	</div></div>
</div>
<?php endif; ?>

<div class="table-responsive">
	<table class="table table-striped">
		<thead><tr><th>ID</th><th><?php echo t('name'); ?></th><th>Phone</th><th>Availability</th><th>Max Distance</th><th>Skills</th><th><?php echo t('action'); ?></th></tr></thead>
		<tbody>
			<?php foreach ($vols as $v): ?>
			<tr>
				<td><?php echo (int)$v['ID']; ?></td>
				<td><?php echo htmlspecialchars($v['name']); ?></td>
				<td><?php echo htmlspecialchars($v['phone no']); ?></td>
				<td><?php echo htmlspecialchars($v['availability']); ?></td>
				<td><?php echo htmlspecialchars($v['Max_distance']); ?> km</td>
				<td><?php echo htmlspecialchars($v['skills']); ?></td>
				<td class="d-flex gap-2">
					<?php if (is_admin()): ?>
					<form method="post" class="d-inline">
						<?php echo csrf_field(); ?>
						<input type="hidden" name="action" value="delete">
						<input type="hidden" name="id" value="<?php echo (int)$v['ID']; ?>">
						<button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this volunteer?')"><?php echo t('delete'); ?></button>
					</form>
					<button class="btn btn-sm btn-secondary" data-bs-toggle="collapse" data-bs-target="#edit<?php echo (int)$v['ID']; ?>"><?php echo t('edit'); ?></button>
					<?php else: ?>
					<span class="text-muted"><?php echo t('view_only'); ?></span>
					<?php endif; ?>
				</td>
			</tr>
			<?php if (is_admin()): ?>
			<tr class="collapse" id="edit<?php echo (int)$v['ID']; ?>">
				<td colspan="7">
					<form method="post" class="card card-body">
						<?php echo csrf_field(); ?>
						<input type="hidden" name="action" value="edit">
						<input type="hidden" name="id" value="<?php echo (int)$v['ID']; ?>">
						<div class="row g-2">
							<div class="col-md-3"><input name="name" class="form-control" value="<?php echo htmlspecialchars($v['name']); ?>" required></div>
							<div class="col-md-3"><input name="phone" class="form-control" value="<?php echo htmlspecialchars($v['phone no']); ?>"></div>
							<div class="col-md-2"><input name="availability" class="form-control" value="<?php echo htmlspecialchars($v['availability']); ?>"></div>
							<div class="col-md-2"><input name="max_distance" class="form-control" value="<?php echo htmlspecialchars($v['Max_distance']); ?>"></div>
							<div class="col-md-2"><input name="skills" class="form-control" value="<?php echo htmlspecialchars($v['skills']); ?>"></div>
						</div>
						<div class="mt-2"><button class="btn btn-primary"><?php echo t('save'); ?></button></div>
					</form>
				</td>
			</tr>
			<?php endif; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

