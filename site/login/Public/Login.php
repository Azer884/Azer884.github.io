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

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['token']) && isset($_POST['token']) && $_SESSION['token'] == $_POST['token']) {
    $email = $_POST['email'];
    $password = $_POST['pass'];

    if (empty($email) || empty($password)) {
        $Error = "Please enter both email and password.";
    }

    if (empty($Error)) {
        $stmt = $conn->prepare("SELECT id, username, url_address, password FROM Users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $url_address, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['url_address'] = $url_address;
                header("Location: index.php");
                exit();
            } else {
                $Error = "Invalid password.";
            }
        } else {
            $Error = "No user found with that email address.";
        }

        $stmt->close();
    }
}

$_SESSION['token'] = RandStr(60);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../Private/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Login | Devjourney</title>
</head>
<body>

    <!----------------------- Main Container -------------------------->
    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <!----------------------- Login Container -------------------------->

        <div class="row border rounded-5 p-3 bg-white shadow box-area">

            <!--------------------------- Left Box ----------------------------->

            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box">
                <img src="../Private/images/LoginLeftImage.jpg" id="left-image" class="img-fluid rounded-4" alt="Background Image">
            </div>

            <!-------------------- ------ Right Box ---------------------------->

            <div class="col-md-6 right-box">
                <form method="post" action="" class="row align-items-center">
                    <div class="header-text mb-4">
                        <h2 id="header-title">Hello, Again</h2>
                        <p id="header-subtitle">We are happy to have you back.</p>
                    </div>
                    <div id="extra-fields"></div>
                    <div class="input-group mb-3">
                        <input type="text" id="email" name="email" value="<?= htmlspecialchars($email) ?>" class="form-control form-control-lg bg-light fs-6" placeholder="Email address" required>
                    </div>
                    <div class="input-group mb-1">
                        <input type="password" id="pass" name="pass" class="form-control form-control-lg bg-light fs-6" placeholder="Password" required>
                    </div>
                    <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
                    <div id="login-extras" class="input-group mb-5 d-flex justify-content-between">
                        <div class="forgot">
                            <small><a href="#" id="forgot-password" class="text-end">Forgot Password?</a></small>
                        </div>
                    </div>
                    <p id="message" class="mb-3 text-danger"><?= htmlspecialchars($Error) ?></p>
                    <div class="input-group mb-3">
                        <input type="submit" value="Sign in" id="action-button" class="btn btn-lg btn-primary w-100 fs-6"></input>
                    </div>
                    <div class="input-group mb-3">
                        <button id="google-button" class="btn btn-lg btn-light w-100 fs-6"><img src="../Private/images/google.png" style="width:20px" class="me-2"><small>Sign In with Google</small></button>
                    </div>
                    <div class="row justify-content-center">
                        <small id="sign-up-container">Don't have an account? <a href="SignUp.html" id="sign-up">Sign Up</a></small>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <img src="../Private/images/Logo.jpg" alt="Logo" id="logo">
</body>
</html>
