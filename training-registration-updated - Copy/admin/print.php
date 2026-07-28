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
$total = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html>
<head>
<title>LetsCode - Printable Report</title>
<style>
    * { box-sizing: border-box; font-family: Arial, Helvetica, sans-serif; }
    body { margin: 30px; color: #111; }
    .report-header { display:flex; justify-content:space-between; align-items:flex-end; border-bottom:2px solid #7c3aed; padding-bottom:14px; margin-bottom:20px; }
    .report-header h1 { font-size:22px; margin:0; }
    .report-header p { font-size:12px; color:#555; margin-top:4px; }
    table { width:100%; border-collapse:collapse; font-size:12px; }
    th, td { border:1px solid #ccc; padding:8px 10px; text-align:left; }
    th { background:#7c3aed; color:white; }
    tr:nth-child(even) { background:#f5f3ff; }
    .no-print { margin-bottom:20px; }
    .no-print button { background:#7c3aed; color:white; border:none; padding:10px 20px; border-radius:8px; cursor:pointer; font-size:14px; }
    .empty { text-align:center; padding:40px; color:#666; }

    @media print {
        .no-print { display:none; }
        body { margin:0; }
    }
</style>
</head>
<body>

<div class="no-print">
    <button onclick="window.print()">🖨 Print / Save as PDF</button>
</div>

<div class="report-header">
    <div>
        <h1>LetsCode - Student Registrations</h1>
        <p><?= $search !== "" ? "Filtered by: \"" . htmlspecialchars($search) . "\" — " : "" ?><?= $total ?> record<?= $total == 1 ? "" : "s" ?></p>
    </div>
    <p>Generated <?= date("Y-m-d H:i") ?></p>
</div>

<?php if ($total === 0): ?>

    <div class="empty">No student registrations found<?= $search !== "" ? " for this search." : "." ?></div>

<?php else: ?>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Age</th>
    <th>Course</th>
    <th>Registered At</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)): ?>
<tr>
    <td><?= $row['ID'] ?></td>
    <td><?= htmlspecialchars($row['full_name']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= htmlspecialchars($row['phone'] ?? '') ?></td>
    <td><?= htmlspecialchars($row['age'] ?? '') ?></td>
    <td><?= htmlspecialchars($row['course']) ?></td>
    <td><?= htmlspecialchars($row['created_at']) ?></td>
</tr>
<?php endwhile; ?>

</table>

<?php endif; ?>

<script>
    // Auto-open the print dialog for convenience; user can cancel.
    window.addEventListener('load', () => setTimeout(() => window.print(), 300));
</script>

</body>
</html>
