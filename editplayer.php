<?php
//Include database connection
Include('connect.php');

//Variable for the player_id which is taken from the URI
$id = mysqli_real_escape_string($conn, $_GET['player_id']);
//Query to select the player with the ID from the URI
$query="SELECT * FROM players WHERE player_id='$id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);
//Variables to put in the html
$pn= $row['playername'];
$pr= $row['rating'];
$pv= $row['value'];
//Check if submit button is set
if(isset($_POST['submit']))
{
    $playername = $_POST['playername'];
    $rating = $_POST['rating'];
    $value = $_POST['value'];

    //If any of the forms are blank, echo feedback message.
    if ($playername == '' || $rating == '' || $value == '')
    {
        echo "Niet alle velden zijn ingevuld";
    }
    else
    {
        //Otherwise, execute this query
        $sql = "UPDATE players SET playername = '$playername', rating='$rating', value='$value' WHERE player_id='$id'";
        $res=mysqli_query($conn, $sql);

        if($res) //If it worked echo confirmation message
        {
            echo "Gegevens Succesvol aangepast";
        }
        else //Otherwise echo an error message
        {
            echo "Er is iets misgegaan!";
        }
    }
}
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
    <form name="editplayer" method="post" enctype="multipart/form-data" action="#">
        <p>Naam speler<br>
            <input type="text" name="playername" value="<?php echo $pn ?>"></p>
        <p>Rating speler<br>
            <input type="text" name="rating" value="<?php echo $pr ?>"></p>
        <p>Waarde speler<br>
            <input type="text" name="value" value="<?php echo $pv ?>"></p>
        <input type="submit" name="submit" value="Pas aan">
    </form>
</body>
</html>
