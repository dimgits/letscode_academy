<?php
session_start();
session_destroy();
header("Location: portal_login.php");
exit();
