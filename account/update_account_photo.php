<!DOCTYPE html>
<?php
session_start();
require './Database.php';
global $pdo;  
?>
<html lang="tr">
  <head>
    <title>Fotoğrafımı Güncelle</title>
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
		<label for="searchword">Arama Seçenekleri</label>
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
			<div class= "update_img_container">
				<form method="POST" enctype="multipart/form-data">
				  <table>
				    <tr>
					    <td>Mevcut fotoğraf:</td>
					    <td><img width=400 height=400 src="<?php echo '..'.$_SESSION['mem_photo'];?>"/></td>
				    </tr>
			      <tr>
			      	<td>Yeni fotoğraf seç:</td>
			      	<td><input type="file" name="product_photo" id="product_photo"></td>
			      </tr>
			  	  <tr colspan="2">
			  	    <td><input type="submit" name="update_photo_button" value="Kaydet"></td>
			  	  </tr>
			  	</table>
			  </form>
			  <?php
			  if(isset($_POST["update_photo_button"])) {
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
                  move_uploaded_file($file_tmp,"../images/person/".$file_name); 
                  $path = "/images/person/".$file_name;                   
                }
            }

            $query2 = 'UPDATE bitkidb.member SET mem_photo = :mem_photo WHERE mem_id = :mem_id';
            $values2 = array(':mem_photo' => $path, ':mem_id' => $_SESSION["mem_id"]);
            try {
              $result2 = $pdo->prepare($query2);
              $result2->execute($values2);
            } catch(PDOException $e) {
              echo "Sorgu hatası";
            }
            $_SESSION["mem_photo"] = $path;
            header("Location: /bitkialisverissite/account/update_account_photo.php");
          }
          unset($result);
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