<?php
/**
 * Database Update Script
 * Run this file to add the new columns for emergency alert tracking
 */

// Database configuration
$host = 'localhost';
$dbname = 'senior_citizen_assistantship';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Database Update Script</h2>";
    echo "<p>Adding new columns to emergency_alert table...</p>";
    
    // Add the new columns
    $sql = "ALTER TABLE emergency_alert 
            ADD COLUMN responded_by INT DEFAULT NULL,
            ADD COLUMN responded_at DATETIME DEFAULT NULL,
            ADD COLUMN resolved_by INT DEFAULT NULL,
            ADD COLUMN resolved_at DATETIME DEFAULT NULL";
    
    $pdo->exec($sql);
    echo "<p style='color: green;'>✅ Successfully added new columns to emergency_alert table!</p>";
    
    // Add indexes for better performance
    $indexes = [
        "CREATE INDEX idx_alert_status ON emergency_alert(alert_status)",
        "CREATE INDEX idx_responded_by ON emergency_alert(responded_by)",
        "CREATE INDEX idx_responded_at ON emergency_alert(responded_at)"
    ];
    
    foreach ($indexes as $index) {
        try {
            $pdo->exec($index);
            echo "<p style='color: green;'>✅ Successfully created index!</p>";
        } catch (PDOException $e) {
            if ($e->getCode() == '42000') { // Index already exists
                echo "<p style='color: orange;'>⚠️ Index already exists, skipping...</p>";
            } else {
                echo "<p style='color: red;'>❌ Error creating index: " . $e->getMessage() . "</p>";
            }
        }
    }
    
    echo "<p style='color: green; font-weight: bold;'>🎉 Database update completed successfully!</p>";
    echo "<p>You can now delete this file (update_database.php) for security.</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
    echo "<p>Please check your database configuration and try again.</p>";
}
?>
