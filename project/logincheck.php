<?php
session_start();

if (!isset($_SESSION["login"])){
    header("Location: login.php?error=Please make sure you login first");
}
?>