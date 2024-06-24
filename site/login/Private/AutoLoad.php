<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
ini_set("display_errors",0);

require "../private/DataBase.php";
require "../private/Functions.php";
?>