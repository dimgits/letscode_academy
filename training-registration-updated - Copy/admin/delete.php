<?php
include "auth.php";
include "../signup_page/db.php";
include "includes/functions.php";

if (!isset($_GET['id'])) {
    header("Location: students.php");
    exit();
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("DELETE FROM tb_registrations WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    flash_set('success', 'Registration deleted.');
} else {
    flash_set('error', 'Could not delete that registration.');
}

// Stay on the Students tab (with whatever page/search the admin was on)
// instead of bouncing back to the dashboard, so multiple deletes in a
// row don't require re-navigating each time.
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$redirect = "students.php?page=" . $page;
if ($search !== '') {
    $redirect .= "&search=" . urlencode($search);
}

header("Location: " . $redirect);
exit();
