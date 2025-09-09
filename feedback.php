<?php
$title = 'Feedback';
include __DIR__ . '/includes/header.php';
require_login();
ensure_csrf();
$db = get_db_connection();

if (is_post()) {
	$action = $_POST['action'] ?? '';
	
	if ($action === 'create') {
		$stmt = $db->prepare('INSERT INTO feedback (rating, comment, user1_id) VALUES (?, ?, ?)');
		$stmt->execute([
			(int)($_POST['rating'] ?? 5),
			trim($_POST['comment'] ?? ''),
			$_SESSION['user']['id'] ?? 1, // Use logged in user's ID
		]);
	} elseif ($action === 'delete' && is_admin()) {
		$id = (int)($_POST['id'] ?? 0);
		$db->prepare('DELETE FROM feedback WHERE serial_no = ?')->execute([$id]);
	}
}

// Get feedback with user information
$rows = $db->query('SELECT f.*, u.name as user_name FROM feedback f LEFT JOIN user u ON f.user1_id = u.Id ORDER BY f.serial_no DESC')->fetchAll();
?>
<div class="row g-3">
	<div class="col-md-5">
		<div class="card"><div class="card-body">
			<h5 class="card-title mb-3"><?php echo t('feedback'); ?></h5>
			<form method="post">
				<?php echo csrf_field(); ?>
				<input type="hidden" name="action" value="create">
				<div class="mb-2">
					<label class="form-label">Rating</label>
					<select name="rating" class="form-control" required>
						<option value="5">⭐⭐⭐⭐⭐ Excellent (5)</option>
						<option value="4">⭐⭐⭐⭐ Very Good (4)</option>
						<option value="3">⭐⭐⭐ Good (3)</option>
						<option value="2">⭐⭐ Fair (2)</option>
						<option value="1">⭐ Poor (1)</option>
					</select>
				</div>
				<div class="mb-2">
					<label class="form-label">Comment</label>
					<textarea name="comment" class="form-control" rows="4" placeholder="Your feedback" required></textarea>
				</div>
				<button class="btn btn-primary"><?php echo t('save'); ?></button>
			</form>
		</div></div>
	</div>
	<div class="col-md-7">
		<div class="card"><div class="card-body">
			<h5 class="card-title mb-3">Recent Feedback</h5>
			<ul class="list-group">
				<?php foreach ($rows as $r): ?>
				<li class="list-group-item">
					<div class="d-flex justify-content-between align-items-start">
						<strong><?php echo htmlspecialchars($r['user_name'] ?? 'Anonymous'); ?></strong>
						<span class="text-warning">
							<?php echo str_repeat('⭐', (int)$r['rating']); ?>
						</span>
					</div>
					<div class="mt-2"><?php echo nl2br(htmlspecialchars($r['comment'])); ?></div>
					<div class="d-flex justify-content-between align-items-center mt-2">
						<small class="text-muted"><?php echo htmlspecialchars($r['created_at']); ?></small>
						<?php if (is_admin()): ?>
						<form method="post" class="d-inline">
							<?php echo csrf_field(); ?>
							<input type="hidden" name="action" value="delete">
							<input type="hidden" name="id" value="<?php echo (int)$r['serial_no']; ?>">
							<button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this feedback?')"><?php echo t('delete'); ?></button>
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

