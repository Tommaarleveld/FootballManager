<?php
session_start();
include('connect.php');
//Check if role is not set or session role == coach
if(!isset($_SESSION['role']) || $_SESSION['role'] == 'coach')
{
    //Dont show page, die
    die("Je hebt een admin account nodig om deze pagina te kunnen bezoeken");
}
?>