<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
</head>
<body>
<div id="navbar">
    <ul class="navigation">
        <li><a class="navlink" href="index.php">Home</a></li>
        <li><a class="navlink" href="login.php">Login</a></li>
        <li><a class="navlink" href="register.php">Registreer</a></li>
        <li><a class="navlink" href="pickteam.php">Kies een team</a></li>
        <?php 
          //include navbar for loggedin users
          session_start(); include('loggedinnav.php')
        ?>
    </ul>
    <a class="navlink" href="index.php"><img id="logo-image" src="https://upload.wikimedia.org/wikipedia/en/2/28/Football_Manager_logo.png" alt="logo"></a>
</div>


<h2>Alle teams</h2>
<p>Klik op een team om de detailpagina te bekijken</p>
</body>
</html>

<?php
//Include connection with the database
include('connect.php');
//Fill session URL with the current URI
$_SESSION['redirectURL'] = $_SERVER['REQUEST_URI'];

//Query to select details from the table teams, order by money
$query="SELECT team_id, teamname, money FROM teams ORDER BY money DESC";
$result = mysqli_query($conn, $query);

//Create a table for the data
echo "<table border='1'>
<tr>
<th>Teamnaam</th>
<th>Money</th>
</tr>";
//While loop to assign rows to table
while($row = mysqli_fetch_array($result))
{
    $resid=$row;
    $id = $row['team_id'];

    echo "<tr>";
    echo "<td><a href='teamprofile.php?team_id=$id'<p>" . $row['teamname'] . "</p></td>";
    echo "<td>" . $row['money'] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>
