<?php
require("../Private/AutoLoad.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_data = CheckLogin($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>
<body>
    <div>Hi <?=$_SESSION['username'] ?></div>
    <div>
        <a href="Logout.php">Logout</a>
    </div>
</body>
</html>