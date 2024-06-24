<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require("../Private/AutoLoad.php");
$Error = "";
$email = "";
$name = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['name'];
    if (!preg_match("/^[a-zA-Z][a-zA-Z0-9_-]{2,15}$/", $name)) {
        $Error = "Please enter a valid Username";
    }
    $email = $_POST['email'];
    if (!preg_match("/^[\w\-]+@[\w\-]+\.[\w\-]+$/", $email)) {
        $Error = "Please enter a valid email";
    }

    $stmt = $conn->prepare("SELECT id FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $Error = "This email is already in use.";
    }

    $stmt->close();

    $password = $_POST['pass'];
    if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
        $Error = "Please enter a valid Password";
    }
    $password2 = $_POST['pass2'];
    if ($password !== $password2) {
        $Error = 'Please Confirm Your Password';
    }

    if (empty($Error)) {
        $date = date('Y-m-d H:i:s');
        $url_address = RandStr(60);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO Users (url_address, username, email, password, date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $url_address, $name, $email, $hashed_password, $date);

        if ($stmt->execute()) {
            echo "User registered successfully.";
            header("Location: Login.php");
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp</title>
</head>
<body>
    <form method="post">
        <div>SignUp</div><br><br>
        <div><?php if (isset($Error) && $Error != "") { echo $Error; } ?></div>
        <label for="name">Username:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
        <br>
        <label for="pass">Password:</label>
        <input type="password" id="pass" name="pass" required>
        <br>
        <label for="pass2">Confirm Password:</label>
        <input type="password" id="pass2" name="pass2" required>
        <br>
        <input type="submit" value="Submit">
        <a href="Login.php">Login</a>
    </form>
</body>
</html>