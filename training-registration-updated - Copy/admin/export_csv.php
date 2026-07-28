<?php
include "auth.php";
include "../signup_page/db.php";

$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

$where = "";
if ($search != "") {
    $where = "WHERE full_name LIKE '%$search%'
              OR email LIKE '%$search%'
              OR course LIKE '%$search%'";
}

$result = mysqli_query($conn, "SELECT * FROM tb_registrations $where ORDER BY created_at DESC");

$filename = "students_" . date("Y-m-d_His") . ".csv";

header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");

$output = fopen("php://output", "w");

// Excel-friendly UTF-8 BOM
fwrite($output, "\xEF\xBB\xBF");

fputcsv($output, ["ID", "Full Name", "Email", "Phone", "Age", "Course", "Registered At"]);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['ID'],
        $row['full_name'],
        $row['email'],
        $row['phone'] ?? '',
        $row['age'] ?? '',
        $row['course'],
        $row['created_at'],
    ]);
}

fclose($output);
exit();
