<!DOCTYPE html>
<?php
session_start();
require './Database.php';
global $pdo;  
?>
<html lang="tr">
  <head>
    <title>Mağaza Aç</title>
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
    <?php 
    // burada şifre bilgilerini getir
    try {   
        $query = 'SELECT mem_firstname, mem_lastname, mem_tel, mem_email, mem_address FROM bitkidb.member WHERE mem_id = :mem_id ';
        $values = array(':mem_id' => $_SESSION["mem_id"]);
        try {
          $res = $pdo->prepare($query);
          $res->execute($values);
        } catch (PDOException $e) {
          throw new Exception('Database query error - 1');
        }
        if ($res->rowCount()>0)
        {
          while($row = $res->fetch()) {
            $firstname = $row[0];
            $lastname = $row[1];  
            $tel = $row[2];  
            $email = $row[3];  
            $address = $row[4];                           
          }
        } else {
          echo "No records matching your query were found.";
        }
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    ?>

		<div class="item_section_container">
      <div class= "add_edit_product_container">
        <form method="GET">
          <table>
            <tr>
              <td class="product_form_text">Mağaza Adı: </td>
              <td><input type="text" name="store_name" id="store_name"></td>
            </tr>
            <tr>
              <td colspan="2">
                <input type="submit" name="open_store_button" value="Mağaza Aç">
              </td>
            </tr>
          </table>
        </form>
        </div>
        <?php
        if(isset($_POST['open_store_button'])){
          //yeni mağaza aç
          $query = 'INSERT INTO bitkidb.store (store_name, mem_id) VALUES (:store_name, :mem_id)';
            $values = array(':store_name'=>$_GET['store_name'], ':mem_id' => $_SESSION["mem_id"]);
            try {
              $result = $pdo->prepare($query);
              $result->execute($values);
            } catch(PDOException $e) {
              throw new Exception('Database query error');
            }
            echo $pdo->lastInsertedId;

          //üyenin rolünü değiştir
          $query2 = 'UPDATE bitkidb.member SET mem_role = :mem_role WHERE mem_id = :mem_id';
          $values2 = array(':mem_role'=> "store_owner_member", ':mem_id' => $_SESSION["mem_id"]);
            try {
              $result2 = $pdo->prepare($query2);
              $result2->execute($values2);
            } catch(PDOException $e) {
              echo "Sorgu hatası";
            }
            $_SESSION["mem_role"] = "store_owner_member";
            //header("Location: /bitkialisverissite/account/open_store.php");
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