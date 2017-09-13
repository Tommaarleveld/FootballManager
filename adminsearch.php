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
            <h2>Zoekformulier</h2>
            <form action="adminsearch.php" method="post">
                <h3>Zoek in de volgende tabellen</h3>
                <p>Gebruik het dropdownmenu om te kiezen in welke tabellen je wilt zoeken</p>
                <select name="table">
                    <option value="users">Gebruikers</option>
                    <option value="players">Spelers</option>
                    <option value="teams">Teams</option>
                </select>
                <input type="text" name="search">
                <input type="submit" name="searchSubmit" value="Zoek">
            </form>
        </div>
        </body>
        </html>

<?php
include ("connect.php");
//Check if submit button for search form is set
if(isset($_POST['search']))
{   //Search input value
    $searchInput = $_POST['search'];
    //Variable to check which case has to be executed
    $table = $_POST['table'];

    //Switch with three cases for three different search queries. This way we can search multiple tables and multiple rows
    switch ($table) {
        //Case if user wants to search for players
        case "players";
            $mysqlQuery = "SELECT * FROM players WHERE playername LIKE '%$searchInput%' OR player_id LIKE '%$searchInput%'";
            $res = mysqli_query($conn, $mysqlQuery);
            $count = mysqli_num_rows($res);
            //Check if result is empty
            if ($count == 0)
            {
                echo "Geen resultaten gevonden";
            }
            else //Otherwise show the result
            {
                echo "<div class='playerTable'><h2>Zoekresultaat</h2><h3>Dit is het resultaat van uwzoekopdracht</h3><table border='1'>
                    <tr>
                    <th>Naam speler</th>
                    <th>Player ID</th>
                    </tr>";
                while ($row = mysqli_fetch_array($res))
                {
                    $resid = $row;
                    echo "<tr>";
                    echo "<td>" . $row['playername'] . "</td>";
                    echo "<td>" . $row['player_id'] . "</td>";
                    echo "</tr>";
                }
                echo "</table></div>";
            }
            break;
        //Same case but for teams
        case "teams";
            $mysqlQuery = "SELECT * FROM teams WHERE team_id LIKE '%$searchInput%' OR teamname LIKE '%$searchInput%'";
            $res = mysqli_query($conn, $mysqlQuery);
            $count = mysqli_num_rows($res);
            if ($count == 0)
            {
                echo "Geen resultaten gevonden";
            }
            else
            {
                echo "<div class='playerTable'><h2>Zoekresultaat</h2><h3>Dit is het resultaat van uwzoekopdracht</h3><table border='1'>
                    <tr>
                    <th>Teamnaam</th>
                    <th>Team ID</th>
                    </tr>";
                while ($row = mysqli_fetch_array($res))
                {
                    $resid = $row;
                    echo "<tr>";
                    echo "<td>" . $row['teamname'] . "</td>";
                    echo "<td>" . $row['team_id'] . "</td>";
                    echo "</tr>";
                }
                echo "</table></div>";
            }
            break;
        //Same case for users
        case "users";
            $mysqlQuery = "SELECT * FROM users WHERE username LIKE '%$searchInput%' OR user_id LIKE '%$searchInput%' OR email LIKE '%$searchInput%'";
            $res = mysqli_query($conn, $mysqlQuery);
            $count = mysqli_num_rows($res);
            if ($count == 0)
            {
                echo "Geen resultaten gevonden";
            }
            else
            {
                echo "<div class='playerTable'><h2>Zoekresultaat</h2><h3>Dit is het resultaat van uwzoekopdracht</h3><table border='1'>
                    <tr>
                    <th>Gebruikersnaam</th>
                    <th>Gebruikers ID</th>
                    <th>Email</th>
                    </tr>";
                while ($row = mysqli_fetch_array($res)) {
                    $resid = $row;
                    echo "<tr>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['user_id'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "</tr>";
                }
                echo "</table></div>";
            }
    }
}
