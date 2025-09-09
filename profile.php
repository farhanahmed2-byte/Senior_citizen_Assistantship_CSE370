<?php
$title = 'My Profile';
include __DIR__ . '/includes/header.php';
require_login();
ensure_csrf();

$db = get_db_connection();
$user_id = $_SESSION['user']['id'];

// Get user information
$user = $db->query("SELECT * FROM user WHERE Id = $user_id")->fetch();

// Get role-specific information
$volunteer_info = null;
$senior_info = null;

if ($_SESSION['user']['role'] === 'volunteer') {
	$volunteer_info = $db->query("SELECT * FROM volunteer WHERE U_id = $user_id")->fetch();
} elseif ($_SESSION['user']['role'] === 'senior') {
	$senior_info = $db->query("SELECT * FROM senior WHERE u1_id = $user_id")->fetch();
}

$success = '';
$error = '';

if (is_post()) {
	$action = $_POST['action'] ?? '';
	
	if ($action === 'update_profile') {
		try {
			// Update basic user information
			$stmt = $db->prepare('UPDATE user SET name = ?, city = ?, Gender = ?, house_no = ? WHERE Id = ?');
			$stmt->execute([
				trim($_POST['name'] ?? ''),
				trim($_POST['city'] ?? ''),
				$_POST['gender'] ?? '',
				trim($_POST['house_no'] ?? ''),
				$user_id
			]);
			
			// Update role-specific information
			if ($_SESSION['user']['role'] === 'volunteer' && $volunteer_info) {
				$stmt = $db->prepare('UPDATE volunteer SET skills = ?, Max_distance = ? WHERE U_id = ?');
				$stmt->execute([
					trim($_POST['skills'] ?? ''),
					(int)($_POST['max_distance'] ?? 10),
					$user_id
				]);
			} elseif ($_SESSION['user']['role'] === 'senior' && $senior_info) {
				$stmt = $db->prepare('UPDATE senior SET pref_language = ?, medical_cond = ? WHERE u1_id = ?');
				$stmt->execute([
					$_POST['pref_language'] ?? 'English',
					trim($_POST['medical_conditions'] ?? ''),
					$user_id
				]);
			}
			
			$success = 'Profile updated successfully!';
			
			// Refresh user data
			$user = $db->query("SELECT * FROM user WHERE Id = $user_id")->fetch();
			if ($_SESSION['user']['role'] === 'volunteer') {
				$volunteer_info = $db->query("SELECT * FROM volunteer WHERE U_id = $user_id")->fetch();
			} elseif ($_SESSION['user']['role'] === 'senior') {
				$senior_info = $db->query("SELECT * FROM senior WHERE u1_id = $user_id")->fetch();
			}
			
		} catch (Throwable $e) {
			$error = 'Update failed: ' . $e->getMessage();
		}
	} elseif ($action === 'change_password') {
		$current_password = $_POST['current_password'] ?? '';
		$new_password = $_POST['new_password'] ?? '';
		$confirm_password = $_POST['confirm_password'] ?? '';
		
		if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
			$error = 'All password fields are required';
		} elseif ($new_password !== $confirm_password) {
			$error = 'New passwords do not match';
		} elseif (strlen($new_password) < 6) {
			$error = 'New password must be at least 6 characters long';
		} else {
			// Verify current password
			if (password_verify($current_password, $user['password'])) {
				$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
				$db->prepare('UPDATE user SET password = ? WHERE Id = ?')->execute([$hashed_password, $user_id]);
				$success = 'Password changed successfully!';
			} else {
				$error = 'Current password is incorrect';
			}
		}
	}
}
?>

<div class="row">
	<div class="col-md-8">
		<div class="card">
			<div class="card-header">
				<h4 class="mb-0">My Profile</h4>
			</div>
			<div class="card-body">
				<?php if ($success): ?>
					<div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
				<?php endif; ?>
				
				<?php if ($error): ?>
					<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
				<?php endif; ?>
				
				<form method="post">
					<?php echo csrf_field(); ?>
					<input type="hidden" name="action" value="update_profile">
					
					<h5 class="mb-3">Personal Information</h5>
					<div class="row g-3">
						<div class="col-md-6">
							<label class="form-label">Full Name</label>
							<input type="text" name="name" class="form-control" 
								value="<?php echo htmlspecialchars($user['name']); ?>" required>
						</div>
						<div class="col-md-6">
							<label class="form-label">Phone Number</label>
							<input type="text" class="form-control" 
								value="<?php echo htmlspecialchars($user['phone no']); ?>" readonly>
							<small class="text-muted">Phone number cannot be changed</small>
						</div>
					</div>
					
					<div class="row g-3 mt-2">
						<div class="col-md-6">
							<label class="form-label">City</label>
							<input type="text" name="city" class="form-control" 
								value="<?php echo htmlspecialchars($user['city']); ?>" required>
						</div>
						<div class="col-md-6">
							<label class="form-label">Gender</label>
							<select name="gender" class="form-select">
								<option value="">Select Gender</option>
								<option value="Male" <?php echo $user['Gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
								<option value="Female" <?php echo $user['Gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
								<option value="Other" <?php echo $user['Gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
							</select>
						</div>
					</div>
					
					<div class="row g-3 mt-2">
						<div class="col-md-6">
							<label class="form-label">House/Street Number</label>
							<input type="text" name="house_no" class="form-control" 
								value="<?php echo htmlspecialchars($user['house_no'] ?? ''); ?>">
						</div>
						<div class="col-md-6">
							<label class="form-label">Role</label>
							<input type="text" class="form-control" 
								value="<?php echo ucfirst($_SESSION['user']['role']); ?>" readonly>
						</div>
					</div>
					
					<?php if ($_SESSION['user']['role'] === 'volunteer' && $volunteer_info): ?>
					<h5 class="mb-3 mt-4">Volunteer Information</h5>
					<div class="row g-3">
						<div class="col-md-6">
							<label class="form-label">Skills & Expertise</label>
							<textarea name="skills" class="form-control" rows="3"><?php echo htmlspecialchars($volunteer_info['skills']); ?></textarea>
						</div>
						<div class="col-md-6">
							<label class="form-label">Maximum Distance (km)</label>
							<input type="number" name="max_distance" class="form-control" 
								min="1" max="100" value="<?php echo $volunteer_info['Max_distance']; ?>">
						</div>
					</div>
					<?php endif; ?>
					
					<?php if ($_SESSION['user']['role'] === 'senior' && $senior_info): ?>
					<h5 class="mb-3 mt-4">Senior Citizen Information</h5>
					<div class="row g-3">
						<div class="col-md-6">
							<label class="form-label">Preferred Language</label>
							<select name="pref_language" class="form-select">
								<option value="English" <?php echo $senior_info['pref_language'] === 'English' ? 'selected' : ''; ?>>English</option>
								<option value="Spanish" <?php echo $senior_info['pref_language'] === 'Spanish' ? 'selected' : ''; ?>>Spanish</option>
								<option value="French" <?php echo $senior_info['pref_language'] === 'French' ? 'selected' : ''; ?>>French</option>
								<option value="Other" <?php echo $senior_info['pref_language'] === 'Other' ? 'selected' : ''; ?>>Other</option>
							</select>
						</div>
						<div class="col-md-6">
							<label class="form-label">Medical Conditions</label>
							<textarea name="medical_conditions" class="form-control" rows="3"><?php echo htmlspecialchars($senior_info['medical_cond']); ?></textarea>
						</div>
					</div>
					<?php endif; ?>
					
					<div class="mt-4">
						<button type="submit" class="btn btn-primary">Update Profile</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="card">
			<div class="card-header">
				<h5 class="mb-0">Change Password</h5>
			</div>
			<div class="card-body">
				<form method="post">
					<?php echo csrf_field(); ?>
					<input type="hidden" name="action" value="change_password">
					
					<div class="mb-3">
						<label class="form-label">Current Password</label>
						<input type="password" name="current_password" class="form-control" required>
					</div>
					
					<div class="mb-3">
						<label class="form-label">New Password</label>
						<input type="password" name="new_password" class="form-control" minlength="6" required>
					</div>
					
					<div class="mb-3">
						<label class="form-label">Confirm New Password</label>
						<input type="password" name="confirm_password" class="form-control" minlength="6" required>
					</div>
					
					<button type="submit" class="btn btn-warning">Change Password</button>
				</form>
			</div>
		</div>
		
		<div class="card mt-3">
			<div class="card-header">
				<h5 class="mb-0">Account Information</h5>
			</div>
			<div class="card-body">
				<p><strong>User ID:</strong> <?php echo $user['Id']; ?></p>
				<p><strong>Role:</strong> <?php echo ucfirst($_SESSION['user']['role']); ?></p>
				<p><strong>Member Since:</strong> <?php echo date('M d, Y', strtotime($user['created_at'])); ?></p>
				<?php if ($user['DOB']): ?>
					<p><strong>Date of Birth:</strong> <?php echo date('M d, Y', strtotime($user['DOB'])); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
