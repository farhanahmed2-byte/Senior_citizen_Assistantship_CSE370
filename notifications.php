<?php
$title = 'Notifications';
include __DIR__ . '/includes/header.php';
require_login();
ensure_csrf();
$db = get_db_connection();

if (is_post()) {
	$action = $_POST['action'] ?? '';
	
	if ($action === 'create') {
		$stmt = $db->prepare('INSERT INTO notification (message, date, time, user_N_id) VALUES (?, ?, ?, ?)');
		$stmt->execute([
			trim($_POST['message'] ?? ''),
			$_POST['date'] ?? date('Y-m-d'),
			$_POST['time'] ?? date('H:i:s'),
			$_SESSION['user']['id'] ?? 1, // Use logged in user's ID
		]);
	} elseif ($action === 'delete' && is_admin()) {
		$id = (int)($_POST['id'] ?? 0);
		$db->prepare('DELETE FROM notification WHERE ID = ?')->execute([$id]);
	}
}

// Get notifications with user information
$rows = $db->query('SELECT n.*, u.name as user_name FROM notification n LEFT JOIN user u ON n.user_N_id = u.Id ORDER BY n.ID DESC')->fetchAll();
?>
<div class="row g-3">
	<div class="col-md-5">
		<div class="card"><div class="card-body">
			<h5 class="card-title mb-3"><?php echo t('notifications'); ?></h5>
			<form method="post">
				<?php echo csrf_field(); ?>
				<input type="hidden" name="action" value="create">
				<div class="mb-2">
					<label class="form-label">Message</label>
					<textarea name="message" class="form-control" rows="4" placeholder="Notification message" required></textarea>
				</div>
				<div class="mb-2">
					<label class="form-label">Date</label>
					<input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
				</div>
				<div class="mb-2">
					<label class="form-label">Time</label>
					<input type="time" name="time" class="form-control" value="<?php echo date('H:i:s'); ?>" required>
				</div>
				<button class="btn btn-primary"><?php echo t('create'); ?></button>
			</form>
		</div></div>
	</div>
	<div class="col-md-7">
		<div class="card"><div class="card-body">
			<h5 class="card-title mb-3">Recent Notifications</h5>
			<ul class="list-group">
				<?php foreach ($rows as $r): ?>
				<li class="list-group-item">
					<div class="d-flex justify-content-between align-items-start">
						<strong><?php echo htmlspecialchars($r['user_name'] ?? 'System'); ?></strong>
						<small class="text-muted"><?php echo htmlspecialchars($r['date'] . ' ' . $r['time']); ?></small>
					</div>
					<div class="mt-2"><?php echo nl2br(htmlspecialchars($r['message'])); ?></div>
					<div class="d-flex justify-content-between align-items-center mt-2">
						<small class="text-muted">Created: <?php echo htmlspecialchars($r['created_at']); ?></small>
						<?php if (is_admin()): ?>
						<form method="post" class="d-inline">
							<?php echo csrf_field(); ?>
							<input type="hidden" name="action" value="delete">
							<input type="hidden" name="id" value="<?php echo (int)$r['ID']; ?>">
							<button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this notification?')"><?php echo t('delete'); ?></button>
						</form>
						<?php endif; ?>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>
		</div></div>
	</div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

