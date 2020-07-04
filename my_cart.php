<!DOCTYPE html>
<?php
session_start();
require './Database.php';
global $pdo;  
?>
<html lang="tr">
  <head>
    <title>Sepetim</title>
    <meta charset="utf-8">
    <link href="styleary.css" rel="stylesheet" type="text/css"/>
  </head>
<body>
<div class="main_container">
	<div class="header">
		<a href="index_for_member.php"><img src="images/logoary3.png" width="90px" id="logo_image"></a>
		<p id="site_name">Bitki Alışveriş</p>
  	<div id="login_area">
  	  <img src="images/accountary.png" width="50px"><br>
  	  <a href="account/my_orders.php">Hesabım</a><br>
      <a href="logout.php">Çıkış yap</a>
    </div>
    <div id="cart_area">
  	  <img src="images/cartary5.png" width="50px"><br>
  	  <a href="my_cart.php">Sepetim</a>

    </div>

	</div> 

	<div class="search_bar">
		<input type="text" id="searchword">
		<label for="searchword">Arama Seçenekleri</label>
	</div>

	<div class="nav_bar">
	</div>
 
	<div class="main_content">
		<div class='cart_item_container'>
				<?php
				  $product_count = 0;
				  $cart_total_price = 0;
					$query = 'SELECT p.product_name, p.product_explanation, p.product_unit_price, p.product_photo, p.in_store_quantity, s.store_name, c.category_name, u.type_name, pc.toCart_quantity, cr.cart_id FROM  bitkidb.product as p, bitkidb.category as c, bitkidb.unittype as u, bitkidb.store as s, bitkidb.product_cart as pc, bitkidb.cart as cr WHERE cr.member_id = :member_id AND pc.cart_id = cr.cart_id AND p.type_id = u.type_id AND p.category_id = c.category_id AND s.store_id = p.store_id  AND p.product_id = pc.product_id AND cr.cart_status = :cr_status ';
          $values = array(':member_id' => $_SESSION['mem_id'], ':cr_status' => "active");
          try {
            $res = $pdo->prepare($query);
            $res->execute($values);
          } catch (PDOException $e) {
            throw new Exception('Database query error - 1');
          }
          if ($res->rowCount()>0)
          {
            while($row = $res->fetch()) {
            	$product_count++;
            	$cart_total_price +=  $row[2]* $row[8];
            	$cart_id = $row[9];
            	echo "<div class='cart_item'>";
            	echo "<table>";
					    echo "<tr>";
						  echo "<th></th>";
					    echo "<th>Bilgiler</th>";
					    echo "<th>Miktar</th>";
					    echo "<th>Fiyat</th>";
					    echo "</tr>";
              echo "<tr>";
              echo "<td height='180' width='150' rowspan='2'><img src= ".$row[3]." width='120' height='120' style='padding:15px'></td>";
              echo "<td  width='25' style='height:25px'>".$row[0]."</td>";
              echo "<td rowspan='2'>".$row[8]."</td>";
              echo "<td width='200' rowspan='2'>".$row[2]."</td>";
              echo "<td width='200'><button>Güncelle</button></td>";
              echo "</tr>";
              echo "<tr>";
              echo "<td width='200' style='height:25px'>".$row[5]."</td>";
              echo "<td width='200'><button>Sil</button></td>";
              echo "</tr>"; 
              echo "</table>"; 
              echo "</div>";                 
            }            
          } else {
            echo "Sepet Boş";
          }
	   		?>
			</div>

	
		<div class="cart_info_container">
		<p>Sepet Özeti</p>
		<p>Topla Ürün Adedi: <?php echo $product_count; ?></p>
		<p>Toplam Tutar: <?php echo $cart_total_price; ?> Lira</p>
		<form method="POST">
			<input type="submit" name="make_order_button" value="Alışverişi Tamamla">
		</form>
		<?php
		//sipariş ekleme kodlarını yazalım
		if(isset($_POST["make_order_button"])) {
			$order_date = date("Y-m-d");
			$randomString = rand();
			//$trackingNo = md5($randomString);
      $tracking_no = "adgasjk43";
			$query = 'INSERT INTO bitkidb.orders (order_date, order_status, tracking_no, cart_id) VALUES (:order_date, :order_status, :tracking_no, :cart_id)';
      $values = array(':order_date'=>$order_date, ':order_status'=>"waiting", ':tracking_no'=>$tracking_no, ':cart_id'=>$cart_id);
      try {
        $result = $pdo->prepare($query);
        $result->execute($values);
      } catch(PDOException $e) {
        throw new Exception('Database query error');
      }
      header("Location: /bitkialisverissite/my_cart.php"); 
      // cart id yi kullanarak cart ın status unu passive hale getir
      $query2 = 'UPDATE bitkidb.cart SET cart_status = :status WHERE cart_id = :cart_id';
      $values2 = array(':status'=>"passive", ':cart_id'=>$cart_id);
      try {
        $result2 = $pdo->prepare($query2);
        $result2->execute($values2);
      } catch(PDOException $e) {
        throw new Exception('Database query error');
      }
      header("Location: /bitkialisverissite/my_cart.php"); 
      // yeni bir cart nesnesi oluştur kullanıcı ismiyle birlikte
      $query3 = 'INSERT INTO bitkidb.cart (member_id) VALUES (:member_id)';
      $values3 = array(':member_id'=>$_SESSION['mem_id']);
      try {
        $result3 = $pdo->prepare($query3);
        $result3->execute($values3);
      } catch(PDOException $e) {
        throw new Exception('Database query error');
      }
      header("Location: /bitkialisverissite/my_cart.php"); 
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