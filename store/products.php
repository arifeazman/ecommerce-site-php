<!DOCTYPE html>
<?php
session_start();
require './Database.php';
global $pdo;
?>
<html lang="tr">
  <head>
    <title>Ürünler</title>
    <meta charset="utf-8">
    <link href="../styleary.css" rel="stylesheet" type="text/css"/>
  </head>
<body>
<div class="main_container">
	<div class="header">
		<a href="../index_for_member.php"><img src="../images/logoary3.png" width="90px" id="logo_image"></a>
		<p id="site_name">Bitki Alışveriş</p>
  	<div id="login_area">
  	  <img src="../images/accountary.png" width="50px"><br>
  	  <a href="../index_for_member.php">Hesabım</a><br>
      <a href="logout.php">Çıkış yap</a>
    </div>
    <div id="cart_area">
  	  <img src="../images/cartary5.png" width="50px"><br>
  	  <a href="../my_cart.php">Sepetim</a>
    </div>
    <div id="store_area">
  	  <img src="../images/storeary.png" width="50px"><br>
  	  <a href="products.php">Mağazam</a>
    </div>

	</div> 

	<div class="search_bar">
		<input type="text" id="searchword">
		<label for="searchword">Arama Seçenekleri</label>
	</div>

	<div class="nav_bar">
		<a href="add_product.php">Ürün Ekle</a>
	</div>
 
	<div class="main_content">
		<div class="side_menu_container">
			<a href="products.php" class="side_menu_item">Ürünler</a>
			<a href="orders.php" class="side_menu_item">Siparişler</a>
			<a href="update_store_info.php" class="side_menu_item">Bilgileri Güncelle</a>
			<a href="update_store_photo.php" class="side_menu_item">Fotoğrafı Güncelle</a>
		</div>
    <div class="container_for_photo_items">
		<?php
    $store_id = $_SESSION["store_id"]; //bunu member id ile sql den getir
    try {
      global $pdo;
      $query = "SELECT product_id, product_name, product_photo, category_id FROM bitkidb.product WHERE store_id = :store_id";
      $values = array(':store_id' => $store_id);
      try {
          $result = $pdo->prepare($query);
          $result->execute($values);
      } catch (PDOException $e) {
          throw new Exception('Database query error - 1');
      }
      if ($result->rowCount()>0)
      {
        while($row = $result->fetch()) {
          echo "<div class= 'product_img_container'>";
          echo "<img width=200 height=200 src='../".$row['product_photo']."'/>";
          echo "<p>".$row['product_name']."</p>";
          echo "<p>".$row['category_id']."</p>";
          echo "<button class='show_picture_button'>Göster</button>";
          echo "</div>";  
        }
        unset($result);
      } else{
        echo "No records matching your query were found.";
      }
    } catch(PDOException $e){
      die("ERROR: Could not able to execute $query. " . $e->getMessage());
    }
    ?>
		
			
		</div>

	</div>


	<div class="clear"></div>

	<div class="footer">
		copyright © 2020 arifeazman
	</div>

</div>

</body>
</html>