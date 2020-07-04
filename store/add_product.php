<!DOCTYPE html>
<html lang="tr">
  <head>
    <title>Ürün Ekle</title>
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
  	  <a href="../index_with_store.php">Hesabım</a><br>
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
			<div class= "add_edit_product_container">
				<form method="POST"  enctype="multipart/form-data">
          <table>
            <tr>
              <td class="product_form_text">Ürün Adı: </td>
              <td><input type="text" name="product_name" id="product_name"></td>
            </tr>
            <tr>
              <td class="product_form_text">Kategori: </td>
              <td>
                <select name = "category">
                <?php
                global $pdo;
                session_start();
                require './Database.php';
                $sql = 'SELECT category_id, category_name FROM  bitkidb.category';
                $result = $pdo->query($sql);
                if ($result->rowCount()>0) {
                  while ($row = $result->fetch()) {
                    echo "<option value ='" . $row["category_id"] . "'>".$row["category_name"]."</option>";
                  }
                  unset($result);
                }
                ?>
                </select>
              </td>
            </tr>
            <tr>
              <td class="product_form_text">Birim Türü: </td>
              <td>
                <select name = "unit_type">
                <?php
                
                $sql = 'SELECT type_id, type_name FROM  bitkidb.unittype';
                $result = $pdo->query($sql);
                if ($result->rowCount()>0) {
                  while ($row = $result->fetch()) {
                    echo "<option value ='" . $row["type_id"] . "'>".$row["type_name"]."</option>";
                  }
                  unset($result);
                }
                ?>
                </select>
              </td>
            </tr>
            <tr>
              <td class="product_form_text">Birim Fiyatı: </td>
              <td><input type="text" name="unit_price" id="unit_price"></td>
            </tr>
            <tr>
              <td class="product_form_text">Depo Miktarı: </td>
              <td><input type="text" name="stored_quantity" id="stored_quantity"></td>
            </tr>
            <tr>
              <td class="product_form_text">Resim: </td>
              <td><input type="file" name="product_photo" id="product_photo"></td>
            </tr>

            <tr>
              <td colspan="2">
                <input type="submit" name="save_product" value="Kaydet">
              </td>
            </tr>
          </table>
        <?php
        if(isset($_POST["save_product"])) {
            $product_name = $_POST["product_name"];
            $category = $_POST["category"];
            $unit_type = $_POST["unit_type"];
            $unit_price = $_POST["unit_price"];
            $stored_quantity = $_POST["stored_quantity"];
            $path = "";
            if(isset($_FILES['product_photo'])){
                $errors = array();
                $file_name = $_FILES['product_photo']['name'];
                $file_size = $_FILES['product_photo']['size'];
                $file_tmp = $_FILES['product_photo']['tmp_name'];
                $file_type = $_FILES['product_photo']['type'];
                $explode_var = explode('.',$file_name);
                $file_ext = strtolower(end($explode_var));
                $extensions = array("jpeg","jpg","png");
                if(in_array($file_ext, $extensions) === false){
                    $errors[]="extension not allowed, please choose a JPEG or PNG file.";
                }
                if($file_size > 2097152){
                    $errors[]='File size must be exactly 2 MB';
                }
                if(empty($errors)==true){   	
                  move_uploaded_file($file_tmp,"../images/product/".$file_name); 
                  $path = "images/product/".$file_name;                   
                }
            }
            $supply_date = date("Y-m-d");
            $store_id = 1; //member id kullanarak store id yi bul buraya ata diğer kısımlar çalışıyor
            global $pdo;
            $query = 'INSERT INTO bitkidb.product (product_name, product_unit_price, product_photo, in_store_quantity, supply_date, store_id, category_id, type_id) VALUES (:product_name, :product_unit_price, :product_photo, :in_store_quantity, :supply_date, :store_id, :category_id, :type_id)';
            $values = array(':product_name'=>$product_name, ':product_unit_price'=>$unit_price,             ':product_photo'=>$path, ':in_store_quantity'=>$stored_quantity, ':supply_date'=>$supply_date, ':store_id'=>$store_id, ':category_id'=>$category, ':type_id'=>$unit_type);
            try {
              $result = $pdo->prepare($query);
              $result->execute($values);
            } catch(PDOException $e) {
              throw new Exception('Database query error');
            }
          }
          unset($result);
        ?>          
        </form>
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