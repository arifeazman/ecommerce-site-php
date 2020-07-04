<!DOCTYPE html>
<?php
session_start();
require './Database.php';
global $pdo;  
?>

<html lang="tr">
  <head>
    <title>Ürün Bilgisi Göster</title>
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
  	<div id="cart_area" >
  	  <img src="images/cartary5.png" width="50px"><br>
  	  <a href="my_cart.php">Sepetim</a>
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
  	  <img src="images/storeary.png" width="50px"><br>
  	  <a href="store/products.php">Mağazam</a>
    </div>
    
	</div> 

	<div class="search_bar">
		<input type="text" id="searchword">
		<label for="searchword">Arama Seçenekleri</label>
	</div>

	<div class="nav_bar">
		<a href="stores.php">Mağazalar</a>
	</div>
 
	<div class="main_content">
		<div class="side_menu_container">
			<a href="index_for_member.php" class="side_menu_item">Ürünler</a>
      <?php    	
      $sql = 'SELECT category_id, category_name FROM bitkidb.category';
      $result = $pdo->query($sql);
      if ($result->rowCount()>0) {
        while ($row = $result->fetch()) {
          echo "<a class='side_menu_item' href='index_for_member.php?category_selection=".$row['category_id']."'>".$row['category_name']."</a>"; 
        }
        unset($result);
      } else {
        echo "No records matching your query were found.";
      }
      unset($result);
      ?>
	  </div>
    <?php // Burada ürünün bilgilerini getiriyorum
    $show_product_id = $_GET['show_product_id'];
    global $pdo;
    $query = 'SELECT p.product_name, p.product_explanation, p.product_unit_price, p.product_photo, p.in_store_quantity, p.supply_date, s.store_name, c.category_name, u.type_name  FROM  bitkidb.product as p, bitkidb.category as c, bitkidb.unittype as u, bitkidb.store as s  WHERE p.product_id = :show_product_id AND p.category_id = c.category_id AND p.store_id = s.store_id AND p.type_id = u.type_id';
    $values = array(':show_product_id' => $show_product_id);
    try {
      $res = $pdo->prepare($query);
      $res->execute($values);
    } catch (PDOException $e) {
      throw new Exception('Database query error - 1');
    }
    if ($res->rowCount()>0)
    {
      while($row = $res->fetch()) {
        $product_name = $row[0];
        $product_explanation = $row[1]; 
        $product_unit_price = $row[2]; 
        $product_photo = $row[3]; 
        $in_store_quantity = $row[4]; 
        $supply_date = $row[5]; 
        $store_name = $row[6]; 
        $category_name = $row[7]; 
        $type_name = $row[8];                  
      }            
    } else {
      echo "No records matching your query were found.";
 
    }
    ?>
		<div class="container_for_photo_items">
			<div class= "update_img_container">
        <form method="POST">
          <table>
            <tr>
              <td width = "300">Ürünün Adı: <?php echo $product_name; ?> </td>
              <td rowspan="7"><img width=400 height=400 src=<?php echo $product_photo; ?>></td>
            </tr>
            <tr>
              <td>Ürün Açıklaması: <?php echo $product_explanation; ?> </td>
            </tr>
            <tr>
              <td>Birim Fiyatı: <?php echo $product_unit_price; ?> </td>
            </tr>
            <tr>
              <td>Depo miktarı: <?php echo $in_store_quantity; ?> </td>
            </tr>
            <tr>
              <td>Bulunduğu Mağaza: <?php echo $store_name; ?> </td>
            </tr>
            <tr>
              <td>Kategori: <?php echo $category_name; ?> </td>
            </tr>
            <tr>
              <td>Satış birimi: <?php echo $type_name; ?> </td>
            </tr>
            <tr>
              <td><input type="submit" name="add_to_cart_button" value="Sepete Ekle" 
                <?php      
                if(isset($_SESSION['mem_id'])) {
                  echo 'style="visibility:visible;"';
                } else {
                  echo 'style="visibility:hidden;"';
                } 
                ?>
                ></td>
            </tr>
            
          </table>
        </form>
        <?php
        // butona bastığında sepete ekleyen kodu yazalım
        if(isset($_POST["add_to_cart_button"])) {
          
          //cart_id yi bulalım öncelikle
          try {   
            $query = 'SELECT cart_id FROM bitkidb.cart WHERE member_id = :member_id ';
            $values = array(':member_id' => $_SESSION["mem_id"]);
            try {
              $res = $pdo->prepare($query);
              $res->execute($values);
            } catch (PDOException $e) {
              throw new Exception('Database query error - 1');
            }
            if ($res->rowCount()>0)
            {
              while($row = $res->fetch()) {
                $cart_id = $row['cart_id'];                          
              }
            } else {
              echo "No records matching your query were found.";
            }
          } catch (Exception $e) {
            echo $e->getMessage();
          }
          // sepete ekleme miktarını bir yazdım daha sonra değiştirebilirim
          $toCart_quantity = 1;

          // şimdi sepete ekleme kodunu yazalım
          $query = 'INSERT INTO bitkidb.product_cart (product_id, cart_id, toCart_quantity) VALUES (:product_id, :cart_id, :toCart_quantity)';
          $values = array(':product_id'=>$show_product_id, ':cart_id'=>$cart_id,':toCart_quantity'=>$toCart_quantity);
          try {
            $result = $pdo->prepare($query);
            $result->execute($values);
          } catch(PDOException $e) {
            throw new Exception('Database query error');
          }
          
          // şimdi sepetteki toplam fiyatı güncelleyelim
          // cart tablosunda ilgili kısmı değiştirme kodunu yazıp
          // daha sonra birim fiyat ile ekleme miktarını çarparak buraya yazalım

          $new_price = $product_unit_price * $toCart_quantity;
          $query2 = 'UPDATE bitkidb.cart SET total_price = :total_price WHERE cart_id = :cart_id';
          $values2 = array(':total_price'=>$new_price, ':cart_id'=>$cart_id);
          try {
            $result2 = $pdo->prepare($query2);
            $result2->execute($values2);
          } catch(PDOException $e) {
            throw new Exception('Database query error');
          }
        }
        ?>

      </div>
		</div>
		
	</div>


	<div class="clear"></div>

	<div class="footer">
		copyright © 2020 arifeazman
	</div>

</div>

</body>
</html>