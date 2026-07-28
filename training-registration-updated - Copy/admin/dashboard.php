<?php
include "auth.php";
include "../signup_page/db.php";
include "includes/functions.php";

run_admin_migrations($conn);
$admin = get_current_admin($conn);

$chartQuery = mysqli_query($conn,"
SELECT course, COUNT(*) AS total
FROM tb_registrations
GROUP BY course
");

$courses = [];
$totals = [];

while($row = mysqli_fetch_assoc($chartQuery)){
    $courses[] = $row['course'];
    $totals[] = $row['total'];
}

$monthlyQuery = mysqli_query($conn, "
SELECT
    MONTH(created_at) AS month,
    COUNT(*) AS total
FROM tb_registrations
GROUP BY MONTH(created_at)
ORDER BY MONTH(created_at)
");

$months = [];
$monthlyTotals = [];

while($row = mysqli_fetch_assoc($monthlyQuery)){
    $months[] = date("M", mktime(0,0,0,$row['month'],1));
    $monthlyTotals[] = $row['total'];
}



$totalStudentsQuery = mysqli_query($conn, 
    "SELECT COUNT(*) AS total FROM tb_registrations"
);
$totalStudents = mysqli_fetch_assoc($totalStudentsQuery)['total'];

$todayQuery = mysqli_query($conn,
    "SELECT COUNT(*) AS total 
     FROM tb_registrations 
     WHERE DATE(created_at) = CURDATE()"
);

$todayRegistrations = mysqli_fetch_assoc($todayQuery)['total'];

$monthQuery = mysqli_query($conn,
    "SELECT COUNT(*) AS total 
     FROM tb_registrations
     WHERE MONTH(created_at) = MONTH(CURDATE())
     AND YEAR(created_at) = YEAR(CURDATE())"
);

$monthRegistrations = mysqli_fetch_assoc($monthQuery)['total'];

$courseQuery = mysqli_query($conn,
    "SELECT course, COUNT(*) AS total
     FROM tb_registrations
     GROUP BY course
     ORDER BY total DESC
     LIMIT 1"
);

$popularCourse = mysqli_fetch_assoc($courseQuery);

$totalStudents = mysqli_num_rows(
    mysqli_query($conn,"SELECT * FROM tb_registrations")
);

$totalCourses = mysqli_num_rows(
    mysqli_query($conn,"
        SELECT DISTINCT course
        FROM tb_registrations
    ")
);

$today = mysqli_num_rows(
    mysqli_query($conn,"
        SELECT *
        FROM tb_registrations
        WHERE DATE(created_at)=CURDATE()
    ")
);

$recentQuery = mysqli_query($conn,
"SELECT *
FROM tb_registrations
ORDER BY created_at DESC
LIMIT 5");
?>

<!DOCTYPE html>
<html>

<head>

<title>LetsCode Admin</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="assets/css/dashboard.css?v=<?= filemtime(__DIR__ . '/assets/css/dashboard.css') ?>">
<link rel="stylesheet" href="assets/css/admin-ui.css?v=<?= filemtime(__DIR__ . '/assets/css/admin-ui.css') ?>">

</head>

<body style="--accent: <?= htmlspecialchars($admin['theme_accent']) ?>;">

<?php $active = 'dashboard'; include "includes/sidebar.php"; ?>

<div class="main">

<h1>Welcome,
<?= htmlspecialchars($admin['username']) ?>
</h1>

<div class="stats-container">

    <div class="stat-card">
        <h3>Total Students</h3>
        <p><?= $totalStudents ?></p>
    </div>

    <div class="stat-card">
        <h3>Today Registrations</h3>
        <p><?= $todayRegistrations ?></p>
    </div>

    <div class="stat-card">
        <h3>This Month</h3>
        <p><?= $monthRegistrations ?></p>
    </div>

    <div class="stat-card">
        <h3>Popular Course</h3>
        <p><?= $popularCourse ? htmlspecialchars($popularCourse['course']) : 'No Data' ?></p>
    </div>

</div>

<div class="charts">

    <div class="chart-card">
        <h3>Students by Course</h3>
        <div class="chart-canvas-wrap">
            <canvas id="courseChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <h3>Monthly Registrations</h3>
        <div class="chart-canvas-wrap">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

</div>

<div class="card table-card">

<div class="toolbar">
    <h3 style="color:var(--accent); margin:0;">Recent Registrations</h3>
    <a href="students.php" class="btn-export">View all students</a>
</div>

<?php if ($totalStudents == 0): ?>

    <div class="empty-state">
        <h3>No students yet</h3>
        <p>Once someone registers for a course, they'll show up here.</p>
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

</tr>

<?php while($row=mysqli_fetch_assoc($recentQuery)): ?>

<tr>

<td><?= $row['ID'] ?></td>

<td><?= htmlspecialchars($row['full_name']) ?></td>

<td><?= htmlspecialchars($row['email']) ?></td>

<td><?= htmlspecialchars($row['course']) ?></td>

<td><?= htmlspecialchars($row['created_at']) ?></td>

</tr>

<?php endwhile; ?>

</table>
</div>

<?php endif; ?>

</div>

</div>

<?php include "includes/ui_partials.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="assets/js/admin-ui.js?v=<?= filemtime(__DIR__ . '/assets/js/admin-ui.js') ?>"></script>

<script>

const ctx = document.getElementById('courseChart');

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($courses); ?>,
        datasets: [{
            data: <?= json_encode($totals); ?>,
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

const monthlyCtx = document.getElementById('monthlyChart');

new Chart(monthlyCtx,{
    type:'bar',

    data:{
        labels:<?= json_encode($months); ?>,

        datasets:[{
            label:'Registrations',
            data:<?= json_encode($monthlyTotals); ?>
        }]
    },

    options:{
        responsive:true,
        maintainAspectRatio:false,

        scales:{
            y:{
                beginAtZero:true,
                ticks:{
                    precision:0
                }
            }
        }
    }

});

</script>

</body>
</html>
