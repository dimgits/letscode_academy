<?php
include "auth.php";
include "../signup_page/db.php";
include "includes/simple_pdf.php";

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

$pdf = new SimplePDF();

$marginX = 40;
$topY = $pdf->height() - 50;
$bottomLimit = 60;
$rowHeight = 20;

$columns = [
    'id'      => ['label' => 'ID',    'x' => $marginX,       'w' => 30],
    'name'    => ['label' => 'Name',  'x' => $marginX + 35,  'w' => 120],
    'email'   => ['label' => 'Email', 'x' => $marginX + 160, 'w' => 160],
    'course'  => ['label' => 'Course','x' => $marginX + 325, 'w' => 110],
    'date'    => ['label' => 'Date',  'x' => $marginX + 440, 'w' => 100],
];

function truncate($text, $maxChars) {
    $text = (string) $text;
    if (strlen($text) > $maxChars) {
        return substr($text, 0, $maxChars - 1) . '…';
    }
    return $text;
}

function drawHeader($pdf, $columns, $y, $title = true) {
    if ($title) {
        $pdf->text(40, $y + 25, "LetsCode - Student Registrations", 16, true);
        $pdf->text(40, $y + 8, "Generated: " . date("Y-m-d H:i"), 8);
    }
    $pdf->rect(38, $y - 16, 517, 20, 0.85);
    foreach ($columns as $col) {
        $pdf->text($col['x'], $y - 10, $col['label'], 10, true);
    }
    return $y - 30;
}

$y = drawHeader($pdf, $columns, $topY);
$count = 0;

while ($row = mysqli_fetch_assoc($result)) {
    if ($y < $bottomLimit) {
        $pdf->addPage();
        $y = drawHeader($pdf, $columns, $pdf->height() - 50, false);
    }

    $pdf->text($columns['id']['x'], $y, $row['ID'], 9);
    $pdf->text($columns['name']['x'], $y, truncate($row['full_name'], 20), 9);
    $pdf->text($columns['email']['x'], $y, truncate($row['email'], 26), 9);
    $pdf->text($columns['course']['x'], $y, truncate($row['course'], 18), 9);
    $pdf->text($columns['date']['x'], $y, truncate($row['created_at'], 16), 9);

    $y -= $rowHeight;
    $count++;
}

if ($count === 0) {
    $pdf->text(40, $y, "No student registrations found.", 11);
}

$pdf->output("students_" . date("Y-m-d_His") . ".pdf");
