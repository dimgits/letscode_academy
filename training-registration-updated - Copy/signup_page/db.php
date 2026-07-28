<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "training_regis";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Add a column to a table only if it doesn't already exist (safe on every request).
function ensure_column($conn, $table, $column, $definition) {
    $table = mysqli_real_escape_string($conn, $table);
    $column = mysqli_real_escape_string($conn, $column);

    $check = mysqli_query($conn, "SHOW COLUMNS FROM `$table` LIKE '$column'");

    if ($check && mysqli_num_rows($check) === 0) {
        mysqli_query($conn, "ALTER TABLE `$table` ADD COLUMN `$column` $definition");
    }
}

ensure_column($conn, 'tb_registrations', 'is_verified', 'TINYINT(1) NOT NULL DEFAULT 0');
ensure_column($conn, 'tb_registrations', 'verify_token', 'VARCHAR(64) NULL');
ensure_column($conn, 'tb_registrations', 'verified_at', 'DATETIME NULL');
ensure_column($conn, 'tb_registrations', 'student_username', 'VARCHAR(50) NULL');
ensure_column($conn, 'tb_registrations', 'student_password', 'VARCHAR(255) NULL');
ensure_column($conn, 'tb_registrations', 'reset_token', 'VARCHAR(64) NULL');
ensure_column($conn, 'tb_registrations', 'reset_expires', 'DATETIME NULL');

// --- Profile fields ---
ensure_column($conn, 'tb_registrations', 'birthday', 'DATE NULL');
ensure_column($conn, 'tb_registrations', 'location', 'VARCHAR(150) NULL');
ensure_column($conn, 'tb_registrations', 'profile_picture', 'VARCHAR(255) NULL');

// --- Security / 2FA fields ---
ensure_column($conn, 'tb_registrations', 'twofa_enabled', 'TINYINT(1) NOT NULL DEFAULT 0');
ensure_column($conn, 'tb_registrations', 'twofa_secret', 'VARCHAR(64) NULL');
ensure_column($conn, 'tb_registrations', 'pending_email', 'VARCHAR(150) NULL');
ensure_column($conn, 'tb_registrations', 'email_change_token', 'VARCHAR(64) NULL');
ensure_column($conn, 'tb_registrations', 'email_change_expires', 'DATETIME NULL');

// --- Notification preferences ---
ensure_column($conn, 'tb_registrations', 'notify_lessons', 'TINYINT(1) NOT NULL DEFAULT 1');
ensure_column($conn, 'tb_registrations', 'notify_homework', 'TINYINT(1) NOT NULL DEFAULT 1');

// --- Learning progress / streak ---
ensure_column($conn, 'tb_registrations', 'completed_lessons', 'TEXT NULL');
ensure_column($conn, 'tb_registrations', 'active_course', 'VARCHAR(100) NULL');
ensure_column($conn, 'tb_registrations', 'login_streak', 'INT NOT NULL DEFAULT 0');
ensure_column($conn, 'tb_registrations', 'last_login_date', 'DATE NULL');

// --- Multi-course enrollment table ---
// A student normally has one row in tb_registrations (their original signup
// course), but can be enrolled in more than one course over time. This table
// tracks every course a student has access to; tb_registrations.course stays
// as their original/primary enrollment for backward compatibility.
mysqli_query($conn, "
    CREATE TABLE IF NOT EXISTS student_courses (
        id           INT AUTO_INCREMENT PRIMARY KEY,
        student_id   INT NOT NULL,
        course       VARCHAR(100) NOT NULL,
        enrolled_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_student_course (student_id, course),
        FOREIGN KEY (student_id) REFERENCES tb_registrations(ID) ON DELETE CASCADE
    ) ENGINE=InnoDB
");