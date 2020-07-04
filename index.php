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
<div class="main_container">
	<div class="header">
		<a href="index.php"><img src="images/logoary3.png" width="90px" id="logo_image"></a>
		<p id="site_name">Bitki Alışveriş</p>
  	<div id="login_area">
  	  <img src="images/accountary.png" width="50px"><br>
  	  <a href="login.php">Giriş Yap</a><br>
  	  <a href="signup.php">Üye Ol</a><br>
  	</div>
  	<div id="cart_area" style="visibility:hidden;">
  	  <img src="images/cartary5.png" width="50px"><br>
  	  <a>Sepetim</a>
    </div>
    <div id="store_area" style="visibility:hidden;">
  	  <img src="images/storeary.png" width="50px"><br>
  	  <a href="store/products.php">Mağazam</a>
    </div>
	</div> 

	<div class="search_bar">
    <form method="get">
      <input type="text" id="search_word" name="search_word">
      <input type="submit" value="Ürünlerde Ara" id="search_button" name="search_button">
    </form>
	</div>

	<div class="nav_bar">
		<a href="show_stores.php">Mağazalar</a>
	</div>
 
	<div class="main_content">
		<div class="side_menu_container">
			<a href="index.php" class="side_menu_item">Ürünler</a>
      <?php	
      $sql = 'SELECT category_id, category_name FROM bitkidb.category';
      $result = $pdo->query($sql);
      if ($result->rowCount()>0) {
        while ($row = $result->fetch()) {
          echo "<a class='side_menu_item' href='index.php?category_selection=".$row['category_id']."'>".$row['category_name']."</a>"; 
        }
        unset($result);
      } else {
        echo "Ürün bulunmamaktadır.";
      }
      unset($result);
      ?>
		</div>
    <?php    
    if(isset($_GET['category_selection'])){
		  $selected_category_id = $_GET['category_selection'];
		  try {   
        $query = 'SELECT product_id, product_name, product_photo, category_id FROM bitkidb.product WHERE category_id = :category_selection ';
        $values = array(':category_selection' => $selected_category_id);
        try {
          $res = $pdo->prepare($query);
          $res->execute($values);
        } catch (PDOException $e) {
          throw new Exception('Database query error - 1');
        }
        if ($res->rowCount()>0)
        {
          while($row = $res->fetch()) {
            echo "<div class= 'product_img_container'>";
            echo "<img width=200 height=200 src='".$row['product_photo']."'/>";
            echo "<p>".$row['product_name']."</p>";
            echo "<p>".$row['category_id']."</p>";
            echo "<a href='show_product.php?show_product_id=".$row['product_id']."'>Göster</a>";
            echo "</div>";                           
          }
        } else {
          echo "Ürün bulunmamaktadır.";
        }
      } catch (Exception $e) {
        echo $e->getMessage();
      }
		} 
    else if(isset($_GET['search_button'])){
      $search_word = $_GET['search_word'];
      $pattern = '%'.$search_word.'%';
      try {   
        $query = 'SELECT product_id, product_name, product_photo, category_id FROM bitkidb.product WHERE product_name LIKE :pattern';
        $values = array(':pattern' => $pattern);
        try {
          $res = $pdo->prepare($query);
          $res->execute($values);
        } catch (PDOException $e) {
          throw new Exception('Database query error - 1');
        }
        if ($res->rowCount()>0)
        {
          while($row = $res->fetch()) {
            echo "<div class= 'product_img_container'>";
            echo "<img width=200 height=200 src='".$row['product_photo']."'/>";
            echo "<p>".$row['product_name']."</p>";
            echo "<p>".$row['category_id']."</p>";
            echo "<a href='show_product.php?show_product_id=".$row['product_id']."'>Göster</a>";
            echo "</div>";                           
          }
        } else {
          echo "Ürün bulunmamaktadır.";
        }
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    } 
    else {
	  	$sql = 'SELECT p.product_id, p.product_name, p.product_photo, c.category_name  FROM bitkidb.product as p, bitkidb.category as c WHERE p.category_id = c.category_id';
      $result = $pdo->query($sql);
      if ($result->rowCount()>0) {
        while ($row = $result->fetch()) {
          echo "<div class= 'product_img_container'>";
          echo "<img width=200 height=200 src='".$row[2]."'/>";
          echo "<p>".$row[1]."</p>";
          echo "<p>".$row[3]."</p>";
          echo "<a href='show_product.php?show_product_id=".$row['product_id']."'>Göster</a>";
          echo "</div>"; 
        }
      unset($result);
      }
	  } 
    
    ?>
		<div class="container_for_photo_items">			
		</div>
	</div>
  
	<div class="clear"></div>
  
	<div class="footer">
		copyright © 2020 arifeazman
	</div>
</div>
</body>
</html>
