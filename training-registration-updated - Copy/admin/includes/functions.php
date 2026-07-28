<?php
/**
 * Shared helper functions for the admin panel.
 * Requires session_start() to already have been called (auth.php does this).
 */

// Store a one-time flash message in the session (shown as a toast on next page load).
function flash_set($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

// Read (and clear) the pending flash message, if any.
function flash_get() {
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

// Add a column to a table only if it doesn't already exist (safe to call on every request).
if (!function_exists('ensure_column')) {
    function ensure_column($conn, $table, $column, $definition) {
        $table = mysqli_real_escape_string($conn, $table);
        $column = mysqli_real_escape_string($conn, $column);

        $check = mysqli_query($conn, "SHOW COLUMNS FROM `$table` LIKE '$column'");

        if ($check && mysqli_num_rows($check) === 0) {
            mysqli_query($conn, "ALTER TABLE `$table` ADD COLUMN `$column` $definition");
        }
    }
}

// Fetch the logged-in admin's row (including preferences), applying safe defaults.
function get_current_admin($conn) {
    $id = (int) $_SESSION['admin_id'];

    $stmt = $conn->prepare("SELECT id, username, rows_per_page, theme_accent FROM admins WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $admin = $stmt->get_result()->fetch_assoc();

    if (!$admin) {
        return [
            'id' => $id,
            'username' => $_SESSION['admin_username'] ?? 'Admin',
            'rows_per_page' => 10,
            'theme_accent' => '#7c3aed',
        ];
    }

    $admin['rows_per_page'] = (int) ($admin['rows_per_page'] ?: 10);
    $admin['theme_accent'] = $admin['theme_accent'] ?: '#7c3aed';

    return $admin;
}

// Ensure the admins table has the preference columns this app relies on.
function run_admin_migrations($conn) {
    ensure_column($conn, 'admins', 'rows_per_page', 'INT NOT NULL DEFAULT 10');
    ensure_column($conn, 'admins', 'theme_accent', "VARCHAR(20) NOT NULL DEFAULT '#7c3aed'");
}
