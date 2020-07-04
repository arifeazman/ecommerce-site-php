<!DOCTYPE html>
<?php
session_start();
require './Database.php';
global $pdo;  
?>
<html lang="tr">
  <head>
    <title>Siparişler</title>
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
  	  <a href="../account/my_orders.php">Hesabım</a><br>
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
		<div class="item_section_container">
			
			  
				<?php				  
					
					$query = 'SELECT p.product_name, pc.toCart_quantity, c.total_price, o.order_status, o.order_Date FROM bitkidb.orders AS o, bitkidb.cart AS c, bitkidb.product_cart AS pc, bitkidb.product AS p, bitkidb.store AS s WHERE o.cart_id = c.cart_id AND pc.cart_id = c.cart_id AND pc.product_id = p.product_id AND p.store_id = s.store_id AND s.store_id = :store_id';
          $values = array(':store_id' => $_SESSION["store_id"]);
          try {
            $res = $pdo->prepare($query);
            $res->execute($values);
          } catch (PDOException $e) {
            throw new Exception('Database query error - 1');
          }
          if ($res->rowCount()>0)
          {
          	echo "<div class='cart_item'>";
            	echo "<table>";
					    echo "<tr>";
					    echo "<th width='250'>Ürün Adı</th>";
					    echo "<th width='150'>Adet</th>";
					    echo "<th width='150'>Toplam Fiyat</th>";
					    echo "<th width='280'>Sipariş Durumu</th>";
					    echo "<th width='280'>Sipariş Tarihi</th>";
					    echo "</table>"; 
              echo "</div>";  
            while($row = $res->fetch()) {
            	echo "<div class='cart_item'>";
            	echo "<table>";
              echo "<tr>";
              echo "<td width='300'>".$row[0]."</td>";
              echo "<td width= '380'>".$row[1]."</td>";
              echo "<td width='150'>".$row[2]."</td>";
              echo "<td width='150'>".$row[3]."</td>";
              echo "<td width='280'>".$row[4]."</td>";
              echo "</tr>"; 
              echo "</table>"; 
              echo "</div>";                 
            }  
                      
          } else {
            echo "Sipariş Yok";
          }
	   		?>
			</div>
			<div class="cart_item">
		
			</div>
		

	</div>


	<div class="clear"></div>

	<div class="footer">
		copyright © 2020 arifeazman
	</div>

</div>

</body>
</html>