<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="myscripts.js"></script>
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
<div class="login-page">
  <div class="form">
  <h2>Login</h2>
    <form class="login-form" name="login" method="post" action="login.php">
      <input type="text" name="username" placeholder="Gebruikersnaam"/>
      <input type="password" name="password" placeholder="Wachtwoord"/>
      <button type="submit" name="submit">login</button>
      <p class="message">Nog geen account? <a href="register.php">Maak een account aan</a></p>
    </form>
  </div>
</div>

</body>
</html>

<?php
//include database connection
include('connect.php');
if(isset($_POST['submit']))
{
    //Get the value from email and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == '' || $password == '' )
    {
        echo "<p>Niet alle velden zijn ingevuld</p>";
    }
    else
    {
        //Search in the database "users" if the email and password exists
        $query = mysqli_query($conn,"SELECT * FROM users WHERE username='".$username."' && password='".$password."'");

        //If the username and password are found in the database set all the session variables for the user
        if (mysqli_num_rows($query) == 1)
        {
            $row = mysqli_fetch_array($query);

            $_SESSION['loggedIn'] = true;
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['hasteam'] = $row['hasteam'];
            header('Location:'. $_SESSION['redirectURL']);
        }
        else // Otherwise echo error feedback message
        {
            echo "De gebruikersnaam en het wachtwoord komen niet overeen";
        }

    }
}
