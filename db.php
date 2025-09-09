<?php


$DB_server = 'localhost';
$DB_NAME = 'senior_citizen_assistantship';  
$DB_USER = 'root';
$DB_PASS = '';




$conn = mysqli_connect($DB_server, $DB_USER, $DB_PASS, $DB_NAME);


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


mysqli_set_charset($conn, "utf8mb4");





function get_db_connection(): PDO {
    global $DB_server, $DB_NAME, $DB_USER, $DB_PASS;
    
    try {
        $dsn = "mysql:host=$DB_server;dbname=$DB_NAME;charset=utf8mb4";
        $pdo = new PDO($dsn, $DB_USER, $DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

?>

