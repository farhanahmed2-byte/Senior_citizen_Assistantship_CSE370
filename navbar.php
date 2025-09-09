<?php
require_once __DIR__ . '/i18n.php';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<div class="container-fluid">
		<a class="navbar-brand" href="index.php">SCA</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<?php if (!empty($_SESSION['user'])): ?>
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<li class="nav-item"><a class="nav-link" href="volunteers.php"><?php echo t('volunteers'); ?></a></li>
				<li class="nav-item"><a class="nav-link" href="schedules.php"><?php echo t('schedule_requests'); ?></a></li>
				<li class="nav-item"><a class="nav-link" href="alerts.php"><?php echo t('emergency_alerts'); ?></a></li>
				<li class="nav-item"><a class="nav-link" href="feedback.php"><?php echo t('feedback'); ?></a></li>
				<li class="nav-item"><a class="nav-link" href="notifications.php"><?php echo t('notifications'); ?></a></li>
				<li class="nav-item"><a class="nav-link" href="volunteer_schedule.php"><?php echo t('volunteer_schedule'); ?></a></li>
				<li class="nav-item"><a class="nav-link" href="donations.php"><?php echo t('donations'); ?></a></li>
				<li class="nav-item"><a class="nav-link" href="locations.php"><?php echo t('location'); ?></a></li>
				<!-- <li class="nav-item"><a class="nav-link" href="test_languages.php">🌐 Test Languages</a></li> -->
			</ul>
			<?php endif; ?>
			<form class="d-flex" method="post" action="set_lang.php">
				<select name="lang" class="form-select form-select-sm me-2" onchange="this.form.submit()">
					<option value="en" <?php echo current_lang()==='en'?'selected':''; ?>>EN</option>
					<option value="es" <?php echo current_lang()==='es'?'selected':''; ?>>ES</option>
					<option value="bn" <?php echo current_lang()==='bn'?'selected':''; ?>>বাং</option>
					<option value="ja" <?php echo current_lang()==='ja'?'selected':''; ?>>日本語</option>
					<option value="hi" <?php echo current_lang()==='hi'?'selected':''; ?>>हिंदी</option>
				</select>
			</form>
			<ul class="navbar-nav ms-2">
				<?php if (!empty($_SESSION['user'])): ?>
					<li class="nav-item">
						<span class="nav-link">
							<?php if (is_admin()): ?>
								<span class="badge bg-danger me-2">ADMIN</span>
							<?php endif; ?>
							<?php echo htmlspecialchars($_SESSION['user']['name']); ?>
						</span>
					</li>
					<li class="nav-item"><a class="nav-link" href="profile.php"><?php echo t('profile'); ?></a></li>
					<li class="nav-item"><a class="nav-link" href="logout.php"><?php echo t('logout'); ?></a></li>
				<?php else: ?>
					<li class="nav-item"><a class="nav-link" href="login.php"><?php echo t('login'); ?></a></li>
					<li class="nav-item"><a class="nav-link" href="register.php"><?php echo t('register'); ?></a></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</nav>

