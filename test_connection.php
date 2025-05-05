<?php
// Include the config file
include 'BACK END/config.php';

try {
    // Test the connection by executing a simple query
    $stmt = $conn->query("SELECT 1");
    echo "<h2>Database Connection Test</h2>";
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    echo "<p>Connected to database: " . $dbname . "</p>";
    echo "<p>Host: " . $host . "</p>";
    echo "<p>Username: " . $username . "</p>";
    
    // Test if tables exist
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<h3>Existing Tables:</h3>";
    if (count($tables) > 0) {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>" . $table . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: orange;'>⚠️ No tables found in the database.</p>";
    }
    
} catch(PDOException $e) {
    echo "<h2>Database Connection Test</h2>";
    echo "<p style='color: red;'>❌ Connection failed: " . $e->getMessage() . "</p>";
}
?> 