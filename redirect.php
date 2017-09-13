<?php
if (!isset($_SESSION["loggedIn"]))
{
    //Set the session redirect url to current uri and redirect to login
    $_SESSION['redirectURL'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
}
?>