<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="style.css">
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
  <h2>Accountgegevens</h2>
  <form class="register-form" name="registration" method="post" enctype="multipart/form-data" action="register.php">
      <input type="text" name="username" placeholder="Gebruikersnaam"/>
      <input type="password" name="password" placeholder="Wachtwoord"/>
      <input type="password" name="repassword" placeholder="Vul uw wachtwoord opnieuw in"/>
      <input type="text" name="email" placeholder="Email"/>
      <button type="submit" name="submit">Verzenden</button>
      <p class="message">Heeft u al een account? <a href="login.php">Log In</a></p>
    </form>
  </div>
</div>


</body>
</html>

<?php
//include database connection
include ('connect.php');
//Check if the logged in is true in session
if(isset($_SESSION['loggedIn']) == true)
{
    //If so echo the message
    echo "<br><h2>U bent al ingelogd</h2><p>Log uit om een nieuw account te kunnen registreren</p>";
}
else
{
    // If the submit button is not blank, it has been clicked on, now initiating the function
    if(isset($_POST['submit'])!='')
    {
        //If any of the forms are blank, echo feedback message.
        if($_POST['email']=='' || $_POST['password']==''|| $_POST['repassword']=='')
        {
            echo "Niet alle velden zijn ingevuld";
        }
        //If the passwords aren't identical, echo error message.
        else if($_POST['password'] !== $_POST['repassword'])
        {
            echo "De wachtwoorden zijn niet gelijk aan elkaar, probeer het opnieuw";
        }
        // Otherwise, import the values into the database.
        else
        {
            $sql="INSERT INTO users(username, email, password, hasTeam) VALUES ('".$_POST['username']."','".$_POST['email']."',  '".$_POST['password']."', 0)";
            $res=mysqli_query($conn, $sql);

            if($res) //If the query is executed create an email with the variables from the form. Then Send the email using php mail function
            {
                $to      = $_POST['email'];
                $subject = 'Welkom bij PHP Soccermanager!';
                $message = 'U bent succesvol geregistreerd op onze website, veel succes met uw carriere!';
                $header = "From: 0882284@hr.nl\r\n";

                mail($to, $subject, $message, $header);

                header('location: pickteam.php');
            }
            else //Otherwise echo an error message
            {
                echo "Deze gebruikersnaam of email bestaat al";
            }
        }
    }
}
?>
