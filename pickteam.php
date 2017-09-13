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
    <ul>
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
<h2>Kies een team uit!</h2>
<h3>Onderstaande teams zijn dringend op zoek naar een nieuwe coach</h3>
<p>Als je een contract hebt getekend kun je jouw team beheren op de <br>
detailpagina van je team of via de knop in de navigatiebar.</p>
</body>
</html>

<?php
//include database connection and redirect after login
include('connect.php');
include('redirect.php');

$query="SELECT * FROM teams WHERE users_user_id IS NULL";
$result = mysqli_query($conn, $query);

echo "<table border='1'>
<tr>
<th>Teamnaam</th>
<th>Money</th>
<th>Contract</th>
</tr>";
//While loop to assign the result to rows
while($row = mysqli_fetch_array($result))
{
    $resid=$row;
    $id = $row['team_id'];
    echo '<tr>';
    echo '<td><a href="teamprofile.php?team_id=$id"<p>' . $row['teamname'] . '</p></td>';
    echo '<td>' . $row['money'] . '</td>';
    echo '<td><form name="team" action= "pickteam.php" method="post"> <input type="hidden" name="team" value="'.$id.'"><input type="submit" name="submit" value="Teken contract"></form></td>';
    echo '</tr>';
}
echo "</table>";
//Check if submit button has been set
if (isset($_POST['submit']))
{
    //Query to update teams and assign coach
    $sql = "UPDATE teams SET users_user_id = '".$_SESSION['user_id']."' WHERE team_id='".$_POST['team']."'";
    $res=mysqli_query($conn, $sql);

    //Query to check if the user has a team
    $hasTeamCheck = "SELECT hasteam FROM users WHERE user_id=". $_SESSION['user_id'];
    $teamCheckResult = mysqli_query($conn, $hasTeamCheck);
    $row = $teamCheckResult->fetch_assoc();

    if($res) //If the first query worked execute this
    {
        echo "<p>Succes met je carriere!</p>";
        //Confirm that the current user has a team now
        $userQuery = "UPDATE users SET hasteam = 1 WHERE user_id =". $_SESSION['user_id'];
        $userResult=mysqli_query($conn, $userQuery);
        //Also change the session variable for current session
        $_SESSION['hasteam'] = 1;
    }
    else if($row['hasteam'] == 1)//If the user already have a team show message
    {
        echo "<p>Je kunt maar een team beheren per account. Registreer met een ander account als u nog een team wilt beheren</p>";
    }
    else//If something else went wrong with the data
    {
        echo "<p>Er ging iets mis met het verwerken van de data</p>";
    }
}
?>
