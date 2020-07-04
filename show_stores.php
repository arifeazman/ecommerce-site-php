<!DOCTYPE html>
<?php
session_start();
require './Database.php';
global $pdo;  
?>
<html lang="tr">
  <head>
    <title>Mağazaları Göster</title>
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
		<input type="text" id="searchword">
		<label for="searchword">Arama Seçenekleri</label>
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
        echo "No records matching your query were found.";
      }
      unset($result);
      ?>
		</div>
    <?php    

		  try {   
        $sql = 'SELECT store_id, store_name, store_photo FROM bitkidb.store ';
        $result = $pdo->query($sql);
        if ($result->rowCount()>0) {
          while ($row = $result->fetch()) {
            $photo_path = substr($row['store_photo'], 0);
            echo "<div class= 'product_img_container'>";
            echo "<img width=200 height=200 src='".$photo_path."'/>";
            echo "<p>".$row['store_name']."</p>";
            echo "<button class='show_picture_button'>Göster</button>";
            echo "</div>";                           
          }
        } else {
          echo "No records matching your query were found.";
        }
      } catch (Exception $e) {
        echo $e->getMessage();
      }
      unset($result); 
    
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