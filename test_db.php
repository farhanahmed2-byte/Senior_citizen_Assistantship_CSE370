<?php
// Test database connection
echo "<h2>Testing Database Connection</h2>";

try {
    require_once __DIR__ . '/config/db.php';
    
    echo "<p>Testing mysqli connection...</p>";
    if ($conn) {
        echo "<p style='color: green;'>✓ mysqli connection successful</p>";
        echo "<p>Database: " . mysqli_get_dbname($conn) . "</p>";
    } else {
        echo "<p style='color: red;'>✗ mysqli connection failed</p>";
    }
    
    echo "<p>Testing PDO connection...</p>";
    $pdo = get_db_connection();
    echo "<p style='color: green;'>✓ PDO connection successful</p>";
    
    // Test if tables exist
    echo "<h3>Checking Tables:</h3>";
    $tables = ['user', 'volunteer', 'senior', 'emergency_alert', 'request', 'schedule', 'feedback', 'notification'];
    
    foreach ($tables as $table) {
        try {
            $result = $pdo->query("SELECT COUNT(*) FROM `$table`");
            $count = $result->fetchColumn();
            echo "<p style='color: green;'>✓ Table '$table' exists with $count records</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>✗ Table '$table' error: " . $e->getMessage() . "</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

