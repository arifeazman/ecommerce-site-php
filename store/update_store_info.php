<!DOCTYPE html>
<html lang="tr">
  <head>
    <title>Mağaza Bilgisi Güncelle</title>
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
			<div class= "add_edit_product_container">
				<form method="POST">
          <table>
            <tr>
              <td class="product_form_text">Mağaza Adı: </td>
              <td><input type="password" name="store_name" id="store_name"></td>
            </tr>
            <tr>
              <td colspan="2">
                <input type="submit" name="change_store_name_button" value="Mağaza Adını Kaydet">
              </td>
            </tr>
          </table>
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