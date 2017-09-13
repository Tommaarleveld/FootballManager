<?php
//Initiate session
include ('connect.php');
session_start();
//Check if loggedIn has been set in the session
if (isset($_SESSION["loggedIn"]))
{
    //If so, destroy session and header to index
    session_destroy();
    header('location: index.php');
}
else
{
    //Otherwise, confirm that there is no session
    echo "there is no session";
}
?>
