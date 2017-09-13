<?php
//initiate session and include database connection + login redirect
session_start();
Include('connect.php');
include('redirect.php');
//variable for session user_id
$id = $_SESSION['user_id'];
//Select query to select the user data
$query="SELECT * FROM users WHERE user_id='$id' ";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);

//Fill these four rows with session variables
$_SESSION['username'] = $row['username'];
$_SESSION['email'] = $row['email'];
$un = $_SESSION['username'];
$mail = $_SESSION['email'];
//check if submit button has been clicked on
if(isset($_POST['submit'])!='')
{
    //Variables taken from the form with method POST
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];

    //If any of the forms are blank, echo feedback message.
    if ($username == '' || $email == '' || $password == '' || $repassword == '')
    {
        echo "Niet alle velden zijn ingevuld";
    }
    //If the passwords aren't identical, echo error message.
    else if ($_POST['password'] !== $_POST['repassword'])
    {
        echo "De wachtwoorden zijn niet gelijk aan elkaar, probeer het opnieuw";
    }
    else
    {
        //Otherwise execute query and update the users details
        $sql = "UPDATE users SET username = '$username', email='$email', password='$password' WHERE user_id=".$_SESSION['user_id'];
        $res=mysqli_query($conn, $sql);

        if($res) //If it worked echo confirmation message
        {
            header('Location: editprofile.php');
            echo "Gegevens Succesvol aangepast";
        }
        else //Otherwise echo an error message
        {
            echo "Deze gebruikersnaam of email bestaat al, probeer het opnieuw";
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
<div class="login-page">
  <div class="form">
  <h2>Login</h2>
    <form class="login-form" name="editprofile" method="post" action="editprofile.php">
      <p>Gebruikersnaam</p>
      <input type="text" name="username" value="<?php echo $un ?>"/>
      <p>Email</p>
      <input type="email" name="email" value="<?php echo $mail ?>"/>
      <p>Wachtwoord</p>
      <input type="password" name="password" placeholder="Nieuw wachtwoord"/>
      <p>Vul wachtwoord opnieuw in</p>
      <input type="password" name="repassword" placeholder="Vul uw wachtwoord opnieuw in"/>
      <button type="submit" name="submit">Verzenden</button>
    </form>
  </div>
</div>
</body>
</html>
