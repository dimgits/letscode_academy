<?php
session_start();
include "../signup_page/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin["password"])) {

            $_SESSION["admin_id"] = $admin["id"];
            $_SESSION["admin_username"] = $admin["username"];

            header("Location: dashboard.php");
            exit();
        }
    }

    $error = "Invalid username or password.";
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Login</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#0f172a;
    background-image:
        radial-gradient(circle at 15% 20%, rgba(124,58,237,.25), transparent 40%),
        radial-gradient(circle at 85% 80%, rgba(192,132,252,.18), transparent 45%);
    color:white;
    padding:20px;
}

.login-card{
    width:100%;
    max-width:400px;
    background:rgba(255,255,255,.05);
    backdrop-filter:blur(15px);
    border:1px solid rgba(255,255,255,.1);
    border-radius:22px;
    padding:45px 40px;
    box-shadow:0 25px 60px rgba(0,0,0,.4);
    animation:fade-up .4s ease;
}

@keyframes fade-up{
    from{ opacity:0; transform:translateY(16px); }
    to{ opacity:1; transform:translateY(0); }
}

.login-logo{
    font-size:22px;
    font-weight:700;
    color:#a855f7;
    text-align:center;
    margin-bottom:28px;
    letter-spacing:.5px;
}

.login-emoji{
    font-size:42px;
    text-align:center;
    margin-bottom:10px;
}

.login-card h1{
    font-size:28px;
    text-align:center;
    margin-bottom:8px;
}

.login-card .subtitle{
    text-align:center;
    font-size:14px;
    color:rgba(255,255,255,.6);
    margin-bottom:32px;
}

.error-box{
    background:rgba(220,38,38,.15);
    border:1px solid rgba(220,38,38,.4);
    color:#fca5a5;
    padding:12px 14px;
    border-radius:10px;
    font-size:13px;
    margin-bottom:20px;
    text-align:center;
}

.field-group{
    margin-bottom:18px;
}

.field-group label{
    display:block;
    font-size:13px;
    color:rgba(255,255,255,.7);
    margin-bottom:8px;
}

.field-group input{
    width:100%;
    padding:13px 15px;
    border-radius:10px;
    border:1px solid rgba(255,255,255,.15);
    background:rgba(255,255,255,.06);
    color:white;
    font-family:inherit;
    font-size:14px;
    outline:none;
    transition:.2s;
}

.field-group input::placeholder{
    color:rgba(255,255,255,.35);
}

.field-group input:focus{
    border-color:#7c3aed;
    background:rgba(255,255,255,.09);
}

.login-btn{
    width:100%;
    background:#7c3aed;
    color:white;
    border:none;
    padding:14px;
    border-radius:10px;
    font-size:15px;
    font-weight:600;
    font-family:inherit;
    cursor:pointer;
    margin-top:8px;
    transition:.2s;
}

.login-btn:hover{
    background:#6d28d9;
}

.login-footer{
    text-align:center;
    font-size:12px;
    color:rgba(255,255,255,.35);
    margin-top:26px;
}

</style>

</head>
<body>

<div class="login-card">

    <div class="login-logo">LetsCode</div>

    <h1>Admin Dashboard</h1>
    <p class="subtitle">Login with admin credentials to continue.</p>

    <?php if ($error): ?>
        <div class="error-box"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">

        <div class="field-group">
            <label for="username">Username</label>
            <input
                type="text"
                id="username"
                name="username"
                placeholder="Enter your username"
                required
                autofocus>
        </div>

        <div class="field-group">
            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password"
                required>
        </div>

        <button type="submit" class="login-btn">Login</button>

    </form>

    <div class="login-footer">© <?= date("Y") ?> LetsCode. All rights reserved.</div>

</div>

</body>
</html>
