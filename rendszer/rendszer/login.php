<?php
  session_start();
  require("../includes/header.php");

  if (isset($_GET['quit']))
  {
    unset($_SESSION['adminuser']);
  }

  if(isset($_POST['submit']))
  {
    $r = mysqli_query($con, "SELECT * FROM users WHERE username='".$_POST['username']."' AND password='".md5($_POST['password'])."'");
    if (mysqli_num_rows($r)>0)
    {
      $row = mysqli_fetch_assoc($r);
      $_SESSION['adminuser'] = $row;
      unset($_SESSION['adminuser']['password']);
    }
  }

  if (isset($_SESSION['adminuser']))
  {
    header("Location: index.php");
  }




?>
<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bejelentkezés</title>

  
</head>
<body>
      <p Jelentkezz be a folytatáshoz!</p>
      <form action="" method="post">
          <input name="username" required type="text" onchange="document.getElementById('unsuccessful_login').classList.add('hide'); console.log('chng')" class="form-control" placeholder="Felhasználónév" autofocus>
          <input name="password" type="password" onchange="document.getElementById('unsuccessful_login').classList.add('hide');" class="form-control" placeholder="Jelszó">
           <button type="submit" name="submit" >Bejelentkezés</button>
      </form>
</body>
</html>
