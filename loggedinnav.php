<?php
//include connection with database
include('connect.php');
//Check if loggedIn has been set in the session
if (isset($_SESSION['loggedIn']))
{
    //Query to retrieve the possible team from coach
    $query = "SELECT team_id FROM teams WHERE users_user_id =". $_SESSION['user_id'];
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $id=$row['team_id'];

    echo '<div id="loggedNav">';
    //Check if session role is admin, if so we can echo the dashboard
    if($_SESSION['role'] == 'admin')
    {
        echo '<li class="right"><a class="navlink"href="admin.php">Admin | Dashboard</a></li>';
    }
    echo '<li class="right"><a class="navlink"><div id="loggedNav">Welkom terug, '.$_SESSION['username'].'! </div></li>';
    echo '<li class="right"><a class="navlink"href="editprofile.php">Wijzig gegevens</a></li>';
    //Check if the user from current session has a team, if so echo a shortcut to his team
    if ($_SESSION['hasteam'] == 1)
    {
        echo '<li class="right"><a class="navlink"href="teamprofile.php?team_id='.$id.'">Mijn team</a></li>';
    }
    echo '<li class="right"><a class="navlink" href="destroy.php">Logout</a></li>';
    echo '</div>';
}
else
{

}