<?php
$title = 'Location';
include __DIR__ . '/includes/header.php';
require_login();
ensure_csrf();
$db = get_db_connection();

if (is_post()) {
	$action = $_POST['action'] ?? '';
	if ($action === 'update_location') {
		$stmt = $db->prepare('UPDATE senior SET location = ? WHERE ID = ?');
		$stmt->execute([
			trim($_POST['location'] ?? ''),
			(int)($_POST['senior_id'] ?? 1)
		]);
	}
}

// Get seniors with their locations
$rows = $db->query('SELECT s.*, u.name as senior_name FROM senior s LEFT JOIN user u ON s.u1_id = u.Id ORDER BY s.ID DESC')->fetchAll();
?>
<div class="row g-3">
	<div class="col-md-6">
		<div class="card"><div class="card-body">
			<h5 class="card-title mb-3">Update Senior Location</h5>
			<form method="post" id="locForm">
				<?php echo csrf_field(); ?>
				<input type="hidden" name="action" value="update_location">
				<div class="mb-2">
					<label class="form-label">Select Senior</label>
					<select name="senior_id" class="form-control" required>
						<option value="">Select Senior</option>
						<?php foreach ($rows as $senior): ?>
							<option value="<?php echo $senior['ID']; ?>"><?php echo htmlspecialchars($senior['senior_name']); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="mb-2">
					<label class="form-label">Location</label>
					<input name="location" class="form-control" placeholder="Enter address or location" required>
				</div>
				<div class="d-flex gap-2">
					<button type="button" class="btn btn-outline-secondary" id="detectBtn">Detect Current Location</button>
					<button class="btn btn-primary"><?php echo t('save'); ?></button>
				</div>
			</form>
		</div></div>
	</div>
	<div class="col-md-6">
		<div class="card"><div class="card-body">
			
			<ul class="list-group">
				<?php foreach ($rows as $r): ?>
				<li class="list-group-item">
					<div class="d-flex justify-content-between align-items-start">
						<strong><?php echo htmlspecialchars($r['senior_name'] ?? 'Unknown'); ?></strong>
						<small class="text-muted">ID: <?php echo $r['ID']; ?></small>
					</div>
					<div class="mt-1">
						<strong>Location:</strong> <?php echo htmlspecialchars($r['location'] ?? 'Not set'); ?>
					</div>
					<?php if (!empty($r['medical_cond'])): ?>
					<div class="mt-1">
						<strong>Medical:</strong> <?php echo htmlspecialchars($r['medical_cond']); ?>
					</div>
					<?php endif; ?>
				</li>
				<?php endforeach; ?>
			</ul>
		</div></div>
	</div>
</div>

<script>
document.getElementById('detectBtn').addEventListener('click', function() {
	if (!navigator.geolocation) return alert('Geolocation not supported');
	navigator.geolocation.getCurrentPosition(function(pos) {
		const lat = pos.coords.latitude.toFixed(6);
		const lng = pos.coords.longitude.toFixed(6);
		document.querySelector('input[name="location"]').value = `Coordinates: ${lat}, ${lng}`;
	}, function() { alert('Unable to get location'); });
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

