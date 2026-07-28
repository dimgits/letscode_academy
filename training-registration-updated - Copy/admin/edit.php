<?php
include "auth.php";
include "../signup_page/db.php";

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM tb_registrations WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Student not found.");
}

$student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Student</title>

<link rel="stylesheet" href="assets/css/dashboard.css?v=<?= filemtime(__DIR__ . '/assets/css/dashboard.css') ?>">

<style>

.container{
    width:90%;
    max-width:600px;
    margin:50px auto;
    background:#1f2937;
    padding:30px;
    border-radius:15px;
}

input,
select{
    width:100%;
    padding:12px;
    margin:12px 0;
    border:none;
    border-radius:8px;
    font-family:inherit;
}

button{
    background:#7c3aed;
    color:white;
    border:none;
    padding:12px 25px;
    border-radius:8px;
    cursor:pointer;
    transition:.2s;
}

button:hover{
    filter:brightness(.88);
}

.cancel-link{
    display:inline-block;
    margin-left:12px;
    color:#cbd5e1;
    text-decoration:none;
}

.cancel-link:hover{
    text-decoration:underline;
}

@media(max-width:500px){
    .container{
        padding:20px;
        margin:20px auto;
    }
}

</style>

</head>

<body>

<div class="container">

<h2>Edit Student</h2>

<form action="update.php" method="POST">

<input type="hidden" name="id" value="<?= $student['ID'] ?>">

<input
type="text"
name="full_name"
value="<?= htmlspecialchars($student['full_name']) ?>"
required>

<input
type="email"
name="email"
value="<?= htmlspecialchars($student['email']) ?>"
required>

<input
type="text"
name="phone"
value="<?= htmlspecialchars($student['phone']) ?>"
required>

<input
type="number"
name="age"
value="<?= htmlspecialchars($student['age']) ?>"
required>

<select name="course">

<option
value="Web Development"
<?= $student['course']=="Web Development"?"selected":"" ?>>
Web Development
</option>

<option
value="UI/UX Design"
<?= $student['course']=="UI/UX Design"?"selected":"" ?>>
UI/UX Design
</option>

<option
value="Data Science"
<?= $student['course']=="Data Science"?"selected":"" ?>>
Data Science
</option>

</select>

<button type="submit">
Save Changes
</button>

<a href="dashboard.php" class="cancel-link">Cancel</a>

</form>

</div>

</body>

</html>