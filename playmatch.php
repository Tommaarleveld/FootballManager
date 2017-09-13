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
<h2>Wedstrijdresultaat</h2>
</body>
</html>

<?php
Include('connect.php');
//Variable for current team ID taken from the URI
$id = mysqli_real_escape_string($conn, $_GET['team_id']);

//Check if user clicked in submitMatch button
if(isset($_POST['submitMatch']))
{
    //Query to get the total rating from the challenger, save it into a variable
    $challengerTeam = mysqli_query($conn,"SELECT SUM(rating) AS totalRatingChallenger FROM players  WHERE teams_team_id =".$id);
    $row = mysqli_fetch_assoc($challengerTeam);
    $sum1 = $row['totalRatingChallenger'];

    //Query to get the total player rating from the challenged club, save it into a variable
    $challengedTeam = mysqli_query($conn,"SELECT SUM(rating) AS totalRatingChallenged FROM players  WHERE teams_team_id =". $_POST['matchTeam_id']);
    $row = mysqli_fetch_assoc($challengedTeam);
    $sum2 = $row['totalRatingChallenged'];

    //Win chances determined by a number between 0, 1000 + the totalrating * 1.5
    //This way the winner is chosen randomly but the coach with the highest rated team has the biggest chance of winning
    $winChanceChallenger = rand(0, 1000 + ($sum1 * 1.5));
    $winChanceChallenged = rand(0, 1000 + ($sum2 * 1.5));

    //Get the money from challenger
    $getMoneyQuery = "SELECT money FROM teams WHERE team_id=$id ";
    $moneyResult = mysqli_query($conn, $getMoneyQuery);

    //Message to show to challenger which team he played
    $opponentTeam = $_POST['matchTeam_name'];
    echo "<p>Je hebt een wedstrijd gespeeld tegen $opponentTeam</p>";

    //Check which number is higher
    if ($winChanceChallenger >= $winChanceChallenged)
    {
        while($row = mysqli_fetch_array($moneyResult))
        {
            //If the challenger won, add money to database and echo a message
            echo "<p>Je hebt gewonnen, gefeliciteerd! Je hebt $50,- verdiend.<br>Het geldbedrag is bijgeschreven bij je rekening</p>";

            $moneyAfterWin = $row['money'] + 50;
            $updateMoneyQuery= "UPDATE teams SET money = '$moneyAfterWin' WHERE team_id='$id'";
            $updateResult = mysqli_query($conn, $updateMoneyQuery);
        }
    }
    else if($winChanceChallenger < $winChanceChallenged)//If the challenger has a lower number then the challenged
    {
        while($row = mysqli_fetch_array($moneyResult))
        {
            //If the challenger lost, delete $10 from his money. And echo confirmation messaage
            echo "<p>Je hebt helaas verloren en verliest hierdoor $10,-<br>Het geld is van je rekening afgeschreven</p>";

            $moneyAfterWin = $row['money'] - 10;
            $updateMoneyQuery= "UPDATE teams SET money = '$moneyAfterWin' WHERE team_id='$id'";
            $updateResult = mysqli_query($conn, $updateMoneyQuery);
        }
    }
    //Echo button to return to his teamprofile
    echo "<td><form name='back' action='teamprofile.php?team_id=$id' method='post'><input type='submit' name='submitBack' value='Ga Terug'></form>";
}
