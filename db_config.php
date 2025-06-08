
<?php
// Database connection parameters
$host = 'localhost';
$db_name = 'cinema_ticket_booking';
$username = 'root';
$password = '';

// Attempt to establish database connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    // Set error mode to exception for better error handling
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Log error and terminate script execution
    error_log("Database Connection Error: " . $e->getMessage());
    die("Connection failed: " . $e->getMessage());
}
?>
