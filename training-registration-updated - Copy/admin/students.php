<?php
include "auth.php";
include "../signup_page/db.php";
include "includes/functions.php";

run_admin_migrations($conn);
$admin = get_current_admin($conn);

$limit = $admin['rows_per_page'];

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

$search = "";

if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

$where = "";

if($search != ""){
    $where = "WHERE full_name LIKE '%$search%'
              OR email LIKE '%$search%'
              OR course LIKE '%$search%'";
}

$countQuery = mysqli_query($conn,
"SELECT COUNT(*) AS total
FROM tb_registrations
$where");

$totalRows = mysqli_fetch_assoc($countQuery)['total'];

$totalPages = ceil($totalRows / $limit);

$students = mysqli_query($conn,
"SELECT *
FROM tb_registrations
$where
ORDER BY created_at DESC
LIMIT $limit OFFSET $offset");

$exportQuery = "search=" . urlencode($search);
?>

<!DOCTYPE html>
<html>

<head>

<title>Students - LetsCode Admin</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="assets/css/dashboard.css?v=<?= filemtime(__DIR__ . '/assets/css/dashboard.css') ?>">
<link rel="stylesheet" href="assets/css/admin-ui.css?v=<?= filemtime(__DIR__ . '/assets/css/admin-ui.css') ?>">

</head>

<body style="--accent: <?= htmlspecialchars($admin['theme_accent']) ?>;">

<?php $active = 'students'; include "includes/sidebar.php"; ?>

<div class="main">

<h1>Students</h1>

<div class="card table-card" style="margin-top:25px;">

<div class="toolbar">

    <form method="GET" style="display:flex;flex-wrap:wrap;gap:10px;">
        <input
            type="text"
            name="search"
            placeholder="Search by name, email or course..."
            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
            class="search-box">

        <button type="submit" class="search-btn">
            Search
        </button>
    </form>

    <div class="export-actions">
        <a class="btn-export" href="export_csv.php?<?= $exportQuery ?>" target="_blank">Export CSV</a>
        <a class="btn-export" href="export_pdf.php?<?= $exportQuery ?>" target="_blank">Export PDF</a>
        <a class="btn-export" href="print.php?<?= $exportQuery ?>" target="_blank">Printable report</a>
    </div>

</div>

<?php if ($totalRows === 0): ?>

    <div class="empty-state">
        <div class="empty-icon">🗂️</div>
        <?php if ($search !== ""): ?>
            <h3>No matches found</h3>
            <p>No students match "<?= htmlspecialchars($search) ?>". Try a different search.</p>
        <?php else: ?>
            <h3>No students yet</h3>
            <p>Once someone registers for a course, they'll show up here.</p>
        <?php endif; ?>
    </div>

<?php else: ?>

<div class="table-wrap">
<table>

<tr>

<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Course</th>
<th>Date</th>
<th>Action</th>

</tr>

<?php while($row=mysqli_fetch_assoc($students)): ?>

<tr>

<td><?= $row['ID'] ?></td>

<td><?= htmlspecialchars($row['full_name']) ?></td>

<td><?= htmlspecialchars($row['email']) ?></td>

<td><?= htmlspecialchars($row['course']) ?></td>

<td><?= htmlspecialchars($row['created_at']) ?></td>
<td>
    <div class="action-buttons">
        <a href="edit.php?id=<?= $row['ID'] ?>" class="btn-edit">
            ✏ Edit
        </a>

        <a href="delete.php?id=<?= $row['ID'] ?>&page=<?= $page ?>&search=<?= urlencode($search) ?>"
           class="btn-delete"
           data-confirm="This will permanently delete <?= htmlspecialchars($row['full_name']) ?>'s registration."
           data-confirm-title="Delete registration?">
            🗑 Delete
        </a>
    </div>
</td>

</tr>

<?php endwhile; ?>

</table>
</div>

<div class="pagination">

<?php if($page > 1): ?>

<a href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>">← Previous</a>

<?php endif; ?>

<?php for($i=1;$i<=$totalPages;$i++): ?>

<a
class="<?= $page==$i ? 'active-page' : '' ?>"
href="?page=<?= $i ?>&search=<?= urlencode($search) ?>">

<?= $i ?>

</a>

<?php endfor; ?>

<?php if($page < $totalPages): ?>

<a href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>">Next →</a>

<?php endif; ?>

</div>

<?php endif; ?>

</div>

</div>

<?php include "includes/ui_partials.php"; ?>

<script src="assets/js/admin-ui.js?v=<?= filemtime(__DIR__ . '/assets/js/admin-ui.js') ?>"></script>

</body>
</html>
