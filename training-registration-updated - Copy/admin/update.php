<?php

include "auth.php";
include "../signup_page/db.php";
include "includes/functions.php";

$id = $_POST["id"];
$full_name = $_POST["full_name"];
$email = $_POST["email"];
$phone = $_POST["phone"];
$age = $_POST["age"];
$course = $_POST["course"];

$stmt = $conn->prepare("
UPDATE tb_registrations
SET
full_name=?,
email=?,
phone=?,
age=?,
course=?
WHERE id=?
");

$stmt->bind_param(
"sssisi",
$full_name,
$email,
$phone,
$age,
$course,
$id
);

if ($stmt->execute()) {
    flash_set('success', 'Registration updated successfully.');
} else {
    flash_set('error', 'Could not update that registration.');
}

header("Location: dashboard.php");
exit();