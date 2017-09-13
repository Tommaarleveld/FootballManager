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
</body>
</html>

<?php
//include database connection
Include('connect.php');
//Get the id from the uri
$id = mysqli_real_escape_string($conn, $_GET['team_id']);
//Query to define teamowner
$teamOwnerQuery = "SELECT users_user_id FROM teams WHERE team_id= $id";
$userQueryResult = mysqli_query($conn, $teamOwnerQuery);
//Query to define team from this page
$thisTeamQuery = "SELECT * FROM teams WHERE team_id=$id ";
$teamQueryResult = mysqli_query($conn, $thisTeamQuery);
$teamQueryResult2 = mysqli_query($conn, $thisTeamQuery);
//Query to select all teams for matchmaking, select two random teams where team_id is not the id from this team
//LIMIT is set to 2 because sometimes an empty value got selected, which is probably the value from the team on the current page
$allTeamsQuery = "SELECT * FROM (SELECT * FROM teams ORDER BY RAND() LIMIT 2)u WHERE team_id != $id";
$allTeamsResult= mysqli_query($conn, $allTeamsQuery);

$coach = false;
//Check if session logged in has been set
if(isset($_SESSION['loggedIn']))
{
    while ($row = mysqli_fetch_object($userQueryResult))
    {
        //Check if the user id from the session is the same as the foreign key in the teams table
        if($row->users_user_id == $_SESSION['user_id'])
        {
            //If its true, echo the teameditpage
            echo "<h2>Uw teampagina</h2>";
            //Set coach true
            $coach = true;
            //Query to select players which do not have a team yet
            $transfersQuery = "SELECT * FROM players WHERE teams_team_id IS NULL";
            $transfersResult = mysqli_query($conn, $transfersQuery);

            echo "<div class='playerTable'><h2>Transferlijst</h2><table border='1'>
            <tr>
            <th>Naam speler</th>
            <th>Rating</th>
            <th>Value</th>
            <th>Koop aan</th>
            </tr></div>";
            //Assign the rows to the table
            while($row = mysqli_fetch_array($transfersResult))
            {
                $resid=$row;
                $value = $row['value'];
                echo "<tr>";
                echo "<td><p>" . $row['playername'] . "</p></td>";
                echo "<td>" . $row['rating'] . "</td>";
                echo "<td>" . $row['value'] . "</td>";
                //Add a submit button to save the value from current player
                echo "<td><form name='buyPlayer' action='teamprofile.php?team_id=$id' method='post'><input type='hidden' name='value' value='".$row['value']."'><input type='hidden' name='player_id' value='".$row['player_id']."'><input type='submit' name='submitBuy' value='Koop speler'></form></td>";
                echo "</tr>";
                //Check if buy button has been clicked on
                if(isset($_POST['submitBuy']))
                {
                    while($row = mysqli_fetch_array($teamQueryResult))
                    {
                        //Variable for team money after the purchase
                        $moneyAfterPurchase = $row['money'] - $_POST['value'];

                        if($moneyAfterPurchase >= 0) //If user has enough money execute the queries
                        {
                            //Set the new money value
                            $moneyQuery = "UPDATE teams SET money = '$moneyAfterPurchase' WHERE team_id=".$id;
                            $moneyResult = mysqli_query($conn, $moneyQuery);

                            //Assign the foreign key from team to certain player
                            $playerQuery = "UPDATE players SET teams_team_id ='$id' WHERE player_id=". $_POST['player_id'];
                            $playerResult = mysqli_query($conn, $playerQuery);

                            if($playerResult)//If it worked refresh the page and tables
                            {
                                echo "<meta http-equiv='refresh' content='0'>";
                            }
                            else//IF the query didnt work echo error message
                            {
                                echo "Error: Er ging iets mis met het verwerken van de data";
                            }
                        }
                        else//If the team doesnt have enough money echo error message
                        {
                            echo "Je hebt helaas niet genoeg geld om deze speler te kopen";
                        }
                    }
                }
            }
            echo "</table></div>";
            //Section to play a match
            echo "<div class='playerTable'><h2>Wedstrijd Spelen</h2><p>Door hieronder te klikken speel je een wedstrijd tegen<br> een random tegenstander uit de competitie.<br>Bij winst ontvang je $50,- en bij verlies kost het je $10,-</p>";
            //The result of the 2 randomly chosen teams for matchmaking
            if ($allTeamsResult->num_rows >= 1)
            {
                //Get the rows from database and put them in the hidden forms
                $row = $allTeamsResult->fetch_assoc();
                echo "<td><form name='playMatch' action='playmatch.php?team_id=$id' method='post'><input type='hidden' name='matchTeam_id' value='".$row['team_id']."'><input type='hidden' name='matchTeam_name' value='".$row['teamname']."'><input type='submit' name='submitMatch' value='Speel Match'></form></td>";
                echo "</tr>";
            }
            echo "</div>";
        }
    }
}
//Check if team id has been set from the uri, this part is also visible if the user from the current session
//is not the coach
if(isset($_GET['team_id']))
{
    echo "<div class='playerTable'><h2>Teamgegevens</h2><table border='1'>
    <tr>
    <th>Team ID</th>
    <th>Teamnaam</th>
    <th>Money</th>
    </tr>";
    //Assign rows to the table created above from the database
    while ($row = mysqli_fetch_array($teamQueryResult2))
    {
    $resid = $row;
    $id = $row['team_id'];
    echo "<tr>";
    echo"<td>" . $row['team_id'] . "</td>";
    echo "<td>" . $row['teamname'] . "</td>";
    echo "<td>" . $row['money'] . "</td>";
    echo "</tr>";
    }
    echo "</table></div>";

    //Query to select the players from the current team
    $getPlayerQuery = "SELECT * FROM players WHERE teams_team_id=$id ";
    $getPlayerResult = mysqli_query($conn, $getPlayerQuery);

    echo "<div class='playerTable'><h2>Spelers</h2><table border='1'>
    <tr>
    <th>Naam speler</th>
    <th>Rating</th>
    <th>Value</th>";
    //Check if coach is true, if so add the sell button
    if($coach == true)
    {
        echo "<th>Verkoop</th>";
    }
    echo "</tr>";
    //Assign the rows from the result from the $getPlayerResult query to the table
    while ($row = mysqli_fetch_array($getPlayerResult))
    {
        $resid = $row;
        $player = $row['player_id'];
        echo "<tr>";
        echo"<td>" . $row['playername'] . "</td>";
        echo "<td>" . $row['rating'] . "</td>";
        echo "<td>" . $row['value'] . "</td>";
        //If the current user is the coach, add the sell button
        if($coach == true)
        {
            echo "<td><form name='sellPlayer' action='teamprofile.php?team_id=$id' method='post'><input type='hidden' name='playerValue' value='".$row['value']."'><input type='hidden' name='player_id' value='".$row['player_id']."'><input type='submit' name='submitSell' value='Verkoop'></form></td>";
        }
        echo "</tr>";
        //Check if sell button has been clicked on
        if(isset($_POST['submitSell']))
        {
            while($row = mysqli_fetch_array($teamQueryResult))
            {
                //Variable for money after sale to put in the database
                $moneyAfterSale = $row['money'] + $_POST['playerValue'];
                //Query to set the new money value
                $moneyQuery = "UPDATE teams SET money = '$moneyAfterSale' WHERE team_id=".$id;
                $moneyResult = mysqli_query($conn, $moneyQuery);
                //Query to set the foreign key to NULL
                $playerQuery = "UPDATE players SET teams_team_id = NULL WHERE player_id=". $_POST['player_id'];
                $playerResult = mysqli_query($conn, $playerQuery);
                //If the query $playerResult is true, refresh the page and tables
                if($playerResult)
                {
                    echo "<meta http-equiv='refresh' content='0'>";
                }
                else//Otherwise echo error message
                {
                    echo "Error: Er ging iets mis met het verwerken van de data";
                }
            }
        }
    }
    echo "</table></div>";
    //submit button for selling the player
    if(isset($_POST['submit']))
    {

        $updatePlayerQuery= "UPDATE players SET teams_team_id = NULL WHERE player_id=".$_POST['player'];
        $updatePlayerResult=mysqli_query($conn, $updatePlayerQuery);

        if($updatePlayerResult) //If it worked echo confirmation message
        {
            echo "<meta http-equiv='refresh' content='0'>";
        }
        else //Otherwise echo an error message
        {
            echo "Error";
        }
    }
}
?>
