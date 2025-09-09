<?php
$title = 'Register';
include __DIR__ . '/includes/header.php';
ensure_csrf();

$error = '';
$success = '';

if (is_post()) {
	try {
		$db = get_db_connection();
		
		// Get form data
		$first_name = trim($_POST['first_name'] ?? '');
		$last_name = trim($_POST['last_name'] ?? '');
		$phone = trim($_POST['phone'] ?? '');
		$password = $_POST['password'] ?? '';
		$confirm_password = $_POST['confirm_password'] ?? '';
		$city = trim($_POST['city'] ?? '');
		$role = $_POST['role'] ?? '';
		$gender = $_POST['gender'] ?? '';
		$dob = $_POST['dob'] ?? '';
		$house_no = trim($_POST['house_no'] ?? '');
		
		// Role-specific fields
		$skills = trim($_POST['skills'] ?? '');
		$max_distance = (int)($_POST['max_distance'] ?? 10);
		$pref_language = $_POST['pref_language'] ?? 'English';
		$medical_conditions = trim($_POST['medical_conditions'] ?? '');
		
		// Validation
		if (empty($first_name) || empty($last_name) || empty($phone) || empty($password) || empty($city) || empty($role)) {
			$error = 'All required fields must be filled';
		} elseif ($password !== $confirm_password) {
			$error = 'Passwords do not match';
		} elseif (strlen($password) < 6) {
			$error = 'Password must be at least 6 characters long';
		} elseif (!in_array($role, ['volunteer', 'senior'])) {
			$error = 'Invalid role selected';
		} else {
			// Check if phone number already exists
			$stmt = $db->prepare('SELECT Id FROM user WHERE `phone no` = ?');
			$stmt->execute([$phone]);
			if ($stmt->fetch()) {
				$error = 'Phone number already registered';
			} else {
				// Create user account
				$full_name = $first_name . ' ' . $last_name;
				$hashed_password = password_hash($password, PASSWORD_DEFAULT);
				
				$stmt = $db->prepare('INSERT INTO user (name, `phone no`, password, city, Gender, DOB, role, house_no) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
				$stmt->execute([
					$full_name,
					$phone,
					$hashed_password,
					$city,
					$gender,
					$dob ?: null,
					$role,
					$house_no
				]);
				
				$user_id = $db->lastInsertId();
				
				// Create role-specific record
				if ($role === 'volunteer') {
					$stmt = $db->prepare('INSERT INTO volunteer (availability, Max_distance, skills, U_id) VALUES (?, ?, ?, ?)');
					$stmt->execute([
						'Available',
						$max_distance,
						$skills ?: 'General assistance',
						$user_id
					]);
				} elseif ($role === 'senior') {
					$stmt = $db->prepare('INSERT INTO senior (pref_language, medical_cond, location, u1_id) VALUES (?, ?, ?, ?)');
					$stmt->execute([
						$pref_language,
						$medical_conditions ?: 'None specified',
						$city,
						$user_id
					]);
				}
				
				$success = 'Registration successful! You can now login with your phone number and password.';
			}
		}
	} catch (Throwable $e) {
		$error = 'Registration failed: ' . $e->getMessage();
	}
}
?>
<div class="row justify-content-center">
	<div class="col-md-8">
		<div class="card">
			<div class="card-body">
				<h3 class="card-title text-center mb-4">Create Account</h3>
				
				<?php if ($error): ?>
					<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
				<?php endif; ?>
				
				<?php if ($success): ?>
					<div class="alert alert-success">
						<?php echo htmlspecialchars($success); ?>
						<div class="mt-2">
							<a href="login.php" class="btn btn-primary">Go to Login</a>
						</div>
					</div>
				<?php endif; ?>
				
				<form method="post" class="needs-validation" novalidate>
					<?php echo csrf_field(); ?>
					
					<h5 class="mb-3">Personal Information</h5>
					<div class="row g-3">
						<div class="col-md-6">
							<label class="form-label">First Name *</label>
							<input type="text" name="first_name" class="form-control" required 
								value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
						</div>
						<div class="col-md-6">
							<label class="form-label">Last Name *</label>
							<input type="text" name="last_name" class="form-control" required 
								value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
						</div>
					</div>
					
					<div class="row g-3 mt-2">
						<div class="col-md-6">
							<label class="form-label">Phone Number *</label>
							<input type="tel" name="phone" class="form-control" required 
								placeholder="e.g., 1234567890"
								value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
						</div>
						<div class="col-md-6">
							<label class="form-label">City *</label>
							<input type="text" name="city" class="form-control" required 
								value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>">
						</div>
					</div>
					
					<div class="row g-3 mt-2">
						<div class="col-md-4">
							<label class="form-label">Date of Birth</label>
							<input type="date" name="dob" class="form-control" 
								value="<?php echo htmlspecialchars($_POST['dob'] ?? ''); ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label">Gender</label>
							<select name="gender" class="form-select">
								<option value="">Select Gender</option>
								<option value="Male" <?php echo ($_POST['gender'] ?? '') === 'Male' ? 'selected' : ''; ?>>Male</option>
								<option value="Female" <?php echo ($_POST['gender'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
								<option value="Other" <?php echo ($_POST['gender'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label">House/Street Number</label>
							<input type="text" name="house_no" class="form-control" 
								placeholder="e.g., 123 Main St"
								value="<?php echo htmlspecialchars($_POST['house_no'] ?? ''); ?>">
						</div>
					</div>
					
					<h5 class="mb-3 mt-4">Account Details</h5>
					<div class="row g-3">
						<div class="col-md-6">
							<label class="form-label">Role *</label>
							<select name="role" class="form-select" required id="roleSelect">
								<option value="">Select Role</option>
								<option value="volunteer" <?php echo ($_POST['role'] ?? '') === 'volunteer' ? 'selected' : ''; ?>>Volunteer</option>
								<option value="senior" <?php echo ($_POST['role'] ?? '') === 'senior' ? 'selected' : ''; ?>>Senior Citizen</option>
							</select>
						</div>
						<div class="col-md-6">
							<label class="form-label">Password *</label>
							<input type="password" name="password" class="form-control" required 
								minlength="6" placeholder="Minimum 6 characters">
						</div>
					</div>
					
					<div class="row g-3 mt-2">
						<div class="col-md-6">
							<label class="form-label">Confirm Password *</label>
							<input type="password" name="confirm_password" class="form-control" required 
								minlength="6" placeholder="Confirm your password">
						</div>
						<div class="col-md-6">
							<label class="form-label">&nbsp;</label>
							<div class="form-text">Password must be at least 6 characters long</div>
						</div>
					</div>
					
					<!-- Volunteer-specific fields -->
					<div id="volunteerFields" style="display: none;">
						<h5 class="mb-3 mt-4">Volunteer Information</h5>
						<div class="row g-3">
							<div class="col-md-6">
								<label class="form-label">Skills & Expertise</label>
								<textarea name="skills" class="form-control" rows="3" 
									placeholder="e.g., Elderly care, Medical assistance, Transportation, Shopping help"><?php echo htmlspecialchars($_POST['skills'] ?? ''); ?></textarea>
							</div>
							<div class="col-md-6">
								<label class="form-label">Maximum Distance (km)</label>
								<input type="number" name="max_distance" class="form-control" 
									min="1" max="100" value="<?php echo htmlspecialchars($_POST['max_distance'] ?? '10'); ?>">
								<div class="form-text">How far are you willing to travel?</div>
							</div>
						</div>
					</div>
					
					<!-- Senior-specific fields -->
					<div id="seniorFields" style="display: none;">
						<h5 class="mb-3 mt-4">Senior Citizen Information</h5>
						<div class="row g-3">
							<div class="col-md-6">
								<label class="form-label">Preferred Language</label>
								<select name="pref_language" class="form-select">
									<option value="English" <?php echo ($_POST['pref_language'] ?? '') === 'English' ? 'selected' : ''; ?>>English</option>
									<option value="Spanish" <?php echo ($_POST['pref_language'] ?? '') === 'Spanish' ? 'selected' : ''; ?>>Spanish</option>
									<option value="French" <?php echo ($_POST['pref_language'] ?? '') === 'French' ? 'selected' : ''; ?>>French</option>
									<option value="Other" <?php echo ($_POST['pref_language'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
								</select>
							</div>
							<div class="col-md-6">
								<label class="form-label">Medical Conditions</label>
								<textarea name="medical_conditions" class="form-control" rows="3" 
									placeholder="e.g., Diabetes, Hypertension, Arthritis (optional)"><?php echo htmlspecialchars($_POST['medical_conditions'] ?? ''); ?></textarea>
							</div>
						</div>
					</div>
					
					<div class="d-grid gap-2 mt-4">
						<button type="submit" class="btn btn-primary btn-lg">Create Account</button>
					</div>
					
					<div class="text-center mt-3">
						Already have an account? <a href="login.php">Login here</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
// Show/hide role-specific fields based on selection
document.getElementById('roleSelect').addEventListener('change', function() {
	const role = this.value;
	const volunteerFields = document.getElementById('volunteerFields');
	const seniorFields = document.getElementById('seniorFields');
	
	volunteerFields.style.display = role === 'volunteer' ? 'block' : 'none';
	seniorFields.style.display = role === 'senior' ? 'block' : 'none';
});

// Form validation
(function() {
	'use strict';
	window.addEventListener('load', function() {
		var forms = document.getElementsByClassName('needs-validation');
		var validation = Array.prototype.filter.call(forms, function(form) {
			form.addEventListener('submit', function(event) {
				if (form.checkValidity() === false) {
					event.preventDefault();
					event.stopPropagation();
				}
				form.classList.add('was-validated');
			}, false);
		});
	}, false);
})();

// Show fields on page load if role is already selected
document.addEventListener('DOMContentLoaded', function() {
	const roleSelect = document.getElementById('roleSelect');
	if (roleSelect.value) {
		roleSelect.dispatchEvent(new Event('change'));
	}
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
