<?php
include('admincheck.php');
?>
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
<div class="playerTable">
    <h2>Doorzoek de database</h2>
    <p>Klik op de knop hieronder om de database zoekfunctie te gebruiken.</p>
    <form action="adminsearch.php">
        <button class="myButton">Zoekfunctie</button>
    </form>
</div>
<div class="playerTable">
    <h2>Voeg een nieuwe speler toe</h2>
    <h4>Vul onderstaande velden in</h4>
    <form name="editPlayerForm" method="post" enctype="multipart/form-data" action="admin.php">
        <p>Naam Speler<br>
            <input type="text" name="playername" value=""></p>
        <p>Rating<br>
            <input type="text" name="rating" value=""></p>
        <p>Waarde<br>
            <input type="text" name="value" value=""></p>
        <input type="submit" name="submit2" value="Verzenden">
    </form>
</div>
</body>
</html>

<?php
//Get players from database
$query="SELECT * FROM players ORDER BY playername ASC";
$result = mysqli_query($conn, $query);
//Echo table for data
echo "<div class='playerTable'><h2>Algemene spelerslijst</h2><h3>Klik op een speler om deze aan te passen</h3><table border='1'>
<tr>
<th>Naam speler</th>
<th>Rating</th>
<th>Value</th>
<th>Team ID</th>
<th>Verwijder</th>
</tr>";
//Assign rows to table with while loop
while($row = mysqli_fetch_array($result))
{
    $resid=$row;
    $editplayer = $row['player_id'];
    echo "<tr>";
    echo "<td><a href='editplayer.php?player_id=$editplayer'<p>" . $row['playername'] . "</p></td>";
    echo "<td>" . $row['rating'] . "</td>";
    echo "<td>" . $row['value'] . "</td>";
    echo "<td>" . $row['teams_team_id'] . "</td>";
    //Hidden value form with delete button
    echo "<td><form name='player' action='admin.php' method='post'> <input type='hidden' name='player' value='".$editplayer."'><input type='submit' name='submit' value='Verwijder'></form></td>";
    echo "</tr>";
}
echo "</table></div>";
//Check if submitbutton has been clicked on
if(isset($_POST['submit']))
{
    //Delete query for player with chosen value from hidden form
    $sql= "DELETE FROM players WHERE player_id=".$_POST['player'];
    $res=mysqli_query($conn, $sql);

    if($res) //If it worked refresh page and updated table
    {
        echo "<meta http-equiv='refresh' content='0'>";
    }
    else //Otherwise echo an error message
    {
        echo "Er is iets misgegaan met het verwerken van de data";
    }
}
//Check if 2nd submit button is set to add players
if(isset($_POST['submit2'])!='')
{
    //If any of the forms are blank, echo feedback message.
    if($_POST['playername']=='' || $_POST['rating']==''|| $_POST['value']=='')
    {
        echo "Niet alle velden zijn ingevuld";
    }
    // Otherwise, import the values into the database.
    else
    {
        $sql2="INSERT INTO players(playername, rating, value) VALUES ('".$_POST['playername']."','".$_POST['rating']."',  '".$_POST['value']."')";
        $res2=mysqli_query($conn, $sql2);

        if($res2) //If it worked refresh page and table
        {
            echo "<meta http-equiv='refresh' content='0'>";
        }
        else //Otherwise echo an error message
        {
            echo 'Er is iets misgegaan met het verwerken van de data';
        }
    }
}
?>
