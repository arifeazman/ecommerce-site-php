<!DOCTYPE html>
<?php
session_start();
require './Database.php';
global $pdo;  
?>
<html lang="tr">
  <head>
    <title>Şifre Değiştir</title>
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

    <?php 
    // burada şifre bilgilerini getir
    try {   
        $query = 'SELECT mem_password FROM bitkidb.member WHERE mem_id = :mem_id ';
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
            $old_password = $row["mem_password"];                          
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
              <td class="product_form_text">Şifreniz: </td>
              <td><input type="password" name="current_password" id="current_password"></td>
            </tr>
            <tr>
              <td class="product_form_text">Yeni Şifre: </td>
              <td><input type="password" name="new_password" id="new_password"></td>
            </tr>
            <tr>
              <td class="product_form_text">Yeni Şifre Tekrar: </td>
              <td><input type="password" name="check_password" id="check_password"></td>
            </tr>
            <tr>
              <td colspan="2">
                <input type="submit" name="change_password_button" value="Şifreyi Kaydet">
              </td>
            </tr>
          </table>
        </form>
        </div>
        <?php 
        //burada şifreleri karşılaştır ve şifreyi güncelle
        if(isset($_GET['change_password_button'])){
          if($old_password == $_GET['current_password']) {
            if($_GET['new_password'] == $_GET['check_password']){
              $query2 = 'UPDATE bitkidb.member SET mem_password = :mem_password WHERE mem_id = :member_id';
              $values2 = array(':mem_password'=>$_GET['new_password'], ':member_id'=> $_SESSION["mem_id"]);
              try {
                $result2 = $pdo->prepare($query2);
                $result2->execute($values2);
              } catch(PDOException $e) {
                throw new Exception('Database query error');
              }

            } else {
              echo "Aynı şifreyi iki kere giriniz";
            }
          } else {
            echo "Yanlış şifre girildi";
          } 
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