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
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../Private/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Sign Up | Devjourney</title>
</head>
<body>

    <!----------------------- Main Container -------------------------->
    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <!----------------------- Login Container -------------------------->

        <div class="row border rounded-5 p-3 bg-white shadow box-area">

            <!--------------------------- Left Box ----------------------------->

            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box">
                <img src="../Private/images/SignUpLeftImage.png" id="left-image" class="img-fluid rounded-4" alt="Background Image">
            </div>

            <!-------------------- ------ Right Box ---------------------------->

            <div class= "col-md-6 right-box">
                <form method="post" action="" class="row align-items-center">
                    <div class="header-text mb-4">
                        <h2 id="header-title">Welcome!</h2>
                        <p id="header-subtitle">Create your account.</p>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control form-control-lg bg-light fs-6" placeholder="Name" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" id="email" name="email" class="form-control form-control-lg bg-light fs-6" placeholder="Email address" value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="pass" name="pass" class="form-control form-control-lg bg-light fs-6" placeholder="Password" required>
                    </div>
                    <div class="input-group mb-1">
                        <input type="password" class="form-control form-control-lg bg-light fs-6" placeholder="Comfirm Password" id="pass2" name="pass2" required>
                    </div>
                    <div id="login-extras" class="input-group mb-5 d-flex justify-content-between">
                        <div class="forgot mb-5 d-flex justify-content-between">
                            <small style="text-align: right;"><a href="Login.php" id="forgot-password" class="text-end">I have an account...</a></small>
                        </div>
                    </div>
                    <p id="error" class="mb-3 text-danger"><?= htmlspecialchars($Error) ?></p>
                    <div class="input-group mb-3">
                        <input type="submit" id="action-button" class="btn btn-lg btn-primary w-100 fs-6" value="Sign Up"></input>
                    </div>
                    <div class="input-group mb-3">
                        <button id="google-button" class="btn btn-lg btn-light w-100 fs-6"><img src="../Private/images/google.png" style="width:20px" class="me-2"><small>Sign Up with Google</small></button>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <img src="../Private/images/Logo.jpg" alt="Logo" id="logo">
</body>
</html>