<!DOCTYPE html>
<?php
session_start();
require './Database.php';
global $pdo;  
?>
<html lang="tr">
  <head>
    <title>Üye Ol</title>
    <meta charset="utf-8">
    <link href="styleary.css" rel="stylesheet" type="text/css"/>
  </head>
<body>
  <a href="index.php" class="">Anasayfaya Dön</a>
	<div class="signup_container">
    <div class="signup_box">
      <form method="POST"  enctype="multipart/form-data">
        <table>
          <tr>
            <td class="product_form_text">Ad*: </td>
            <td><input type="text" name="first_name" id="first_name"></td>
          </tr>
          <tr>
            <td class="product_form_text">Soyad*: </td>
            <td><input type="text" name="last_name" id="last_name"></td>
          </tr>
          <tr>
              <td class="product_form_text">Şifre*: </td>
              <td><input type="password" name="password" id="password"></td>
            </tr>
          <tr>
            <td class="product_form_text">Telefon: </td>
            <td><input type="text" name="tel" id="tel"></td>
          </tr>
          <tr>
            <td class="product_form_text">Email*: </td>
            <td><input type="text" name="email" id="email"></td>
          </tr>
          <tr>
              <td class="product_form_text">Resim: </td>
              <td><input type="file" name="photo" id="photo"></td>
            </tr>
          <tr>
            <td class="product_form_text">Adres: </td>
            <td><textarea name="address" id="address" rows="4"></textarea></td>
          </tr>
            <tr>
              <td colspan="2">
                <input type="submit" name="signup_button" value="Üye Ol">
              </td>
            </tr>
          </table>
        </form>
      <?php
        if(isset($_POST["signup_button"])) {
            $first_name = $_POST["first_name"];
            $last_name = $_POST["last_name"];
            $password = $_POST["password"];
            $tel = $_POST["tel"];
            $email = $_POST["email"];
            $address = $_POST["address"];
            $path = "";
            if(isset($_FILES['photo'])){
                $errors = array();
                $file_name = $_FILES['photo']['name'];
                $file_size = $_FILES['photo']['size'];
                $file_tmp = $_FILES['photo']['tmp_name'];
                $file_type = $_FILES['photo']['type'];
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
                  move_uploaded_file($file_tmp,"images/person/".$file_name); 
                  $path = "images/person/".$file_name;                   
                }
            }
            $signup_date = date("Y-m-d");
            $mem_role = "regular_member";
            $query = 'INSERT INTO bitkidb.member (mem_firstname, mem_lastname, mem_password, mem_photo, mem_tel, mem_email, mem_address, mem_date, mem_role) VALUES (:mem_firstname, :mem_lastname, :mem_password, :mem_photo, :mem_tel, :mem_email, :mem_address, :mem_date, :mem_role)';
            $values = array(':mem_firstname'=>$first_name, ':mem_lastname'=>$last_name,             ':mem_password'=>$password, ':mem_photo'=> $path, ':mem_tel'=>$tel, ':mem_email'=>$email, ':mem_address'=>$address, ':mem_date'=>$signup_date, ':mem_role'=>$mem_role);
            try {
              $result = $pdo->prepare($query);
              $result->execute($values);
            } catch(PDOException $e) {
              throw new Exception('Database query error');
            }
            //oluşturulan kullanıcının id sini al          
            $mem_id = $pdo->lastInsertID();
            
            //kullanıcıya bir sepet oluştur
            $query3 = 'INSERT INTO bitkidb.cart (member_id) VALUES (:member_id)';
            $values3 = array(':member_id'=> $mem_id);
            try {
              $result3 = $pdo->prepare($query3);
              $result3->execute($values3);
            } catch(PDOException $e) {
              throw new Exception('Database query error');
            }
            unset($result);
            header("Location: /bitkialisverissite/index.php");
          }
          
        ?>
    </form>
    </div>
	</div>
</body>
</html>
