<?php
$title = 'Login';
include __DIR__ . '/includes/header.php';
ensure_csrf();

$error = '';
if (is_post()) {
	try {
		$db = get_db_connection();
		$phone = trim($_POST['phone'] ?? '');
		$password = $_POST['password'] ?? '';
		
		// Check if user exists in the user table
		$stmt = $db->prepare('SELECT * FROM user WHERE `phone no` = ?');
		$stmt->execute([$phone]);
		$user = $stmt->fetch();
		
		if ($user) {
			// Check password - support both hashed and plain text (for backward compatibility)
			$password_valid = false;
			
			if (password_verify($password, $user['password'])) {
				// New hashed password
				$password_valid = true;
			} elseif ($password === $user['password']) {
				// Old plain text password - upgrade to hashed
				$password_valid = true;
				$hashed_password = password_hash($password, PASSWORD_DEFAULT);
				$db->prepare('UPDATE user SET password = ? WHERE Id = ?')->execute([$hashed_password, $user['Id']]);
			}
			
			if ($password_valid) {
				$_SESSION['user'] = [
					'id' => $user['Id'],
					'name' => $user['name'],
					'role' => $user['role'] ?? 'user',
					'phone' => $user['phone no'],
				];
				redirect('index.php');
			} else {
				$error = 'Invalid password';
			}
		} else {
			$error = 'Phone number not found';
		}
	} catch (Throwable $e) {
		$error = 'Login failed: ' . $e->getMessage();
	}
}
?>
<div class="row justify-content-center">
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title mb-3"><?php echo t('login'); ?></h5>
				<?php if ($error): ?>
					<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
				<?php endif; ?>
				<form method="post">
					<?php echo csrf_field(); ?>
					<div class="mb-3">
						<label class="form-label">Phone Number</label>
						<input type="text" name="phone" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label"><?php echo t('password'); ?></label>
						<input type="password" name="password" class="form-control" required>
					</div>
					<button class="btn btn-primary w-100"><?php echo t('sign_in'); ?></button>
				</form>
				
				<div class="text-center mt-3">
					Don't have an account? <a href="register.php">Register here</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>

