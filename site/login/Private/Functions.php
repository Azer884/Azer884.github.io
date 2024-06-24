<?php 
    function RandStr($length) {
        $array = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        $text = "";
        $length = rand(4, $length);

        for ($i = 0; $i < $length; $i++) {
            $random = rand(0, count($array) - 1);
            $text .= $array[$random];
        }

        return $text;
}

// Check login function
function CheckLogin($conn) {
    if (isset($_SESSION['url_address'])) {
        $url_address = $_SESSION['url_address'];
        $stmt = $conn->prepare("SELECT * FROM Users WHERE url_address = ?");
        $stmt->bind_param("s", $url_address);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user_info = $result->fetch_object();
            return $user_info;
        }
    }

    header("Location: Login.php");
    die();
}
?>