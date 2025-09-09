<!-- <?php
$title = 'Language Test';
include __DIR__ . '/includes/header.php';
?>
<div class="container">
    <h1 class="mb-4">Language Test Page</h1>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Current Language: <?php echo strtoupper(current_lang()); ?></h5>
                </div>
                <div class="card-body">
                    <p><strong>App Title:</strong> <?php echo t('app_title'); ?></p>
                    <p><strong>Welcome:</strong> <?php echo t('welcome'); ?></p>
                    <p><strong>Volunteers:</strong> <?php echo t('volunteers'); ?></p>
                    <p><strong>Emergency Alerts:</strong> <?php echo t('emergency_alerts'); ?></p>
                    <p><strong>Dashboard:</strong> <?php echo t('dashboard'); ?></p>
                    <p><strong>Login:</strong> <?php echo t('login'); ?></p>
                    <p><strong>Logout:</strong> <?php echo t('logout'); ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Language Selection</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="set_lang.php">
                        <div class="mb-3">
                            <label for="lang" class="form-label">Select Language:</label>
                            <select name="lang" id="lang" class="form-select" onchange="this.form.submit()">
                                <option value="en" <?php echo current_lang()==='en'?'selected':''; ?>>English (EN)</option>
                                <option value="es" <?php echo current_lang()==='es'?'selected':''; ?>>Español (ES)</option>
                                <option value="bn" <?php echo current_lang()==='bn'?'selected':''; ?>>বাংলা (বাং)</option>
                                <option value="ja" <?php echo current_lang()==='ja'?'selected':''; ?>>日本語 (日本語)</option>
                                <option value="hi" <?php echo current_lang()==='hi'?'selected':''; ?>>हिंदी (हिंदी)</option>
                            </select>
                        </div>
                    </form>
                    
                    <div class="mt-3">
                        <h6>Available Languages:</h6>
                        <ul class="list-unstyled">
                            <li><strong>English (EN):</strong> Senior Citizen Assistance</li>
                            <li><strong>Español (ES):</strong> Asistencia a Personas Mayores</li>
                            <li><strong>বাংলা (বাং):</strong> বয়স্ক নাগরিক সহায়তা</li>
                            <li><strong>日本語 (日本語):</strong> 高齢者支援</li>
                            <li><strong>हिंदी (हिंदी):</strong> वरिष्ठ नागरिक सहायता</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Sample Translations</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h6>Navigation</h6>
                            <p><strong>Volunteers:</strong> <?php echo t('volunteers'); ?></p>
                            <p><strong>Schedule Requests:</strong> <?php echo t('schedule_requests'); ?></p>
                            <p><strong>Feedback:</strong> <?php echo t('feedback'); ?></p>
                        </div>
                        <div class="col-md-3">
                            <h6>Actions</h6>
                            <p><strong>Create:</strong> <?php echo t('create'); ?></p>
                            <p><strong>Edit:</strong> <?php echo t('edit'); ?></p>
                            <p><strong>Delete:</strong> <?php echo t('delete'); ?></p>
                        </div>
                        <div class="col-md-3">
                            <h6>Status</h6>
                            <p><strong>Pending:</strong> <?php echo t('pending'); ?></p>
                            <p><strong>Approved:</strong> <?php echo t('approved'); ?></p>
                            <p><strong>Rejected:</strong> <?php echo t('rejected'); ?></p>
                        </div>
                        <div class="col-md-3">
                            <h6>Forms</h6>
                            <p><strong>Name:</strong> <?php echo t('name'); ?></p>
                            <p><strong>Email:</strong> <?php echo t('email'); ?></p>
                            <p><strong>Password:</strong> <?php echo t('password'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?> -->
