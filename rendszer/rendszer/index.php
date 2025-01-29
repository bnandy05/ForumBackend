<?php
  session_start();
  require("../includes/config.php");
  require("../includes/header.php");
  if (!isset($_SESSION['adminuser'])) header("Location: login.php");
?>

<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Vezérlőpult</title>
</head>
<body>
	<p>Be vagy jelentkezve.</p>
	<p><a href="login.php?quit">Kijelentkezés.</a></p>
</body>
</html>
