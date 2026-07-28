<?php
include "../signup_page/db.php";

$username = "admin";
$password = password_hash("admin123", PASSWORD_DEFAULT);

// Check first instead of blindly inserting -- avoids an uncaught
// mysqli_sql_exception on duplicate key (PHP 8.1+ throws by default
// instead of just returning false).
$check = $conn->prepare("SELECT id FROM admins WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();

if ($check->get_result()->num_rows > 0) {
    echo "Admin already exists -- you can log in with username \"admin\" and password \"admin123\" (or whatever you've since changed it to).";
    exit();
}

$stmt = $conn->prepare("INSERT INTO admins(username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $password);

if ($stmt->execute()) {
    echo "Admin created successfully! Username: admin, Password: admin123";
} else {
    echo "Error: " . $stmt->error;
}
?>