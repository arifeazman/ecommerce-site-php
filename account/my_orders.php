<!DOCTYPE html>
<?php
session_start();
require './Database.php';
global $pdo;  
?>
<html lang="tr">
  <head>
    <title>Siparişlerim</title>
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
  	  <a href="my_orders.php">Hesabım</a><br>
      <a href="../logout.php">Çıkış yap</a>
    </div>
    <div id="cart_area">
  	  <img src="../images/cartary5.png" width="50px"><br>
  	  <a href="../my_cart.php">Sepetim</a>
    </div>
    <div id="store_area"
      <?php      
      if($_SESSION["mem_role"] == "regular_member") {
        echo 'style="visibility:hidden;"';
      } else if($_SESSION["mem_role"] == "store_owner_member") {
        echo 'style="visibility:visible;"';
      }
      ?>
    >
      <img src="../images/storeary.png" width="50px"><br>
      <a href="../store/products.php">Mağazam</a>
    </div>

	</div> 

	<div class="search_bar">
		<input type="text" id="searchword">
		<label for="searchword">Siparişlerimde Ara</label>
	</div>

	<div class="nav_bar">
    <?php
     echo "Hoşgeldin ".$_SESSION["mem_email"];
    ?>
	</div>
 
	<div class="main_content">
		<div class="side_menu_container">
			<a href="my_orders.php" class="side_menu_item">Siparişlerim</a>
			<a href="change_password.php" class="side_menu_item">Şifre Değiştir</a>
			<a href="update_account_info.php" class="side_menu_item">Bilgilerimi Güncelle</a>
			<a href="update_account_photo.php" class="side_menu_item">Fotoğrafımı Güncelle</a>
      <a href="open_store.php" class="side_menu_item"
      <?php      
      if($_SESSION["mem_role"] == "regular_member") {
        echo 'style="visibility:visible;"';
      } else if($_SESSION["mem_role"] == "store_owner_member") {
        echo 'style="visibility:hidden;"';
      }
      ?>
      >Mağaza Aç</a>
		</div>

		<div class="item_section_container">
		  
			  
				<?php				  
					$query = 'SELECT p.product_name, s.store_name, pc.toCart_quantity, c.total_price, o.order_status, o.order_date FROM bitkidb.orders AS o, bitkidb.cart AS c, bitkidb.product_cart AS pc, bitkidb.product AS p, bitkidb.store AS s, bitkidb.member AS m WHERE o.cart_id = c.cart_id AND pc.cart_id = c.cart_id AND c.member_id = m.mem_id AND pc.product_id = p.product_id AND m.mem_id = :mem_id';
          $values = array(':mem_id' => $_SESSION['mem_id']);
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
					    echo "<th width='300'>Bulunduğu Mağaza</th>";
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
              echo "<td width='280'>".$row[5]."</td>";
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