<!DOCTYPE html>
<?php
session_start();
require './Database.php';
global $pdo;  
?>

<html lang="tr">
  <head>
    <title>Bitki Alışveriş Anasayfa</title>
    <meta charset="utf-8">
    <link href="styleary.css" rel="stylesheet" type="text/css"/>
  </head>
<body>
	<p>Çıkış yapılıyor..</p>
	<?php
	session_unset();
	session_destroy();
	header("Location: /bitkialisverissite/index.php");
	?>
</body>