<?php
$title = 'Donations';
include __DIR__ . '/includes/header.php';
require_login();
ensure_csrf();
$db = get_db_connection();

if (is_post()) {
	$action = $_POST['action'] ?? '';
	if ($action === 'create') {
		$stmt = $db->prepare('INSERT INTO donation (donor_name, amount, message) VALUES (?,?,?)');
		$stmt->execute([
			trim($_POST['donor_name'] ?? ''),
			(float)($_POST['amount'] ?? 0),
			trim($_POST['message'] ?? ''),
		]);
	} elseif ($action === 'delete' && is_admin()) {
		$id = (int)($_POST['id'] ?? 0);
		$db->prepare('DELETE FROM donation WHERE id=?')->execute([$id]);
	}
}

$rows = $db->query('SELECT * FROM donation ORDER BY created_at DESC')->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
	<h3><?php echo t('donations'); ?></h3>
	<button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#createForm"><?php echo t('create'); ?></button>
</div>

<div id="createForm" class="collapse mb-4">
	<div class="card"><div class="card-body">
		<form method="post">
			<?php echo csrf_field(); ?>
			<input type="hidden" name="action" value="create">
			<div class="row g-2">
				<div class="col-md-4"><input name="donor_name" class="form-control" placeholder="Donor Name" required></div>
				<div class="col-md-3"><input type="number" step="0.01" name="amount" class="form-control" placeholder="Amount" required></div>
				<div class="col-md-5"><input name="message" class="form-control" placeholder="Message/Purpose"></div>
			</div>
			<div class="mt-3"><button class="btn btn-success"><?php echo t('save'); ?></button></div>
		</form>
	</div></div>
</div>

<div class="table-responsive">
	<table class="table table-striped">
		<thead><tr><th>ID</th><th>Donor</th><th>Amount</th><th>Message</th><th>Date</th><th><?php echo t('action'); ?></th></tr></thead>
		<tbody>
			<?php foreach ($rows as $r): ?>
			<tr>
				<td><?php echo (int)$r['id']; ?></td>
				<td><?php echo htmlspecialchars($r['donor_name']); ?></td>
				<td>$<?php echo number_format((float)$r['amount'], 2); ?></td>
				<td><?php echo htmlspecialchars($r['message']); ?></td>
				<td><?php echo htmlspecialchars($r['created_at']); ?></td>
				<td>
					<?php if (is_admin()): ?>
					<form method="post" class="d-inline">
						<?php echo csrf_field(); ?>
						<input type="hidden" name="action" value="delete">
						<input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
						<button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this donation?')"><?php echo t('delete'); ?></button>
					</form>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

