<!DOCTYPE html>
<?php
session_start();
require './Database.php';
global $pdo;  
?>
<html lang="tr">
  <head>
    <title>Giriş Yap</title>
    <meta charset="utf-8">
    <link href="styleary.css" rel="stylesheet" type="text/css"/>
  </head>
<body>
  
	<div class="login_container">
    <div class="login_box">
	  <form method="POST">
      <table>
        <tr>
          <td class="form_text">E-mail</td>
          <td><input type="text" name="email" id="email"></td>
        </tr>
        <tr>
          <td class="form_text">Şifre</td>
          <td><input type="password" name="password" id="password"></td>
        </tr>
        <tr>
          <td colspan="2">
              <input type="submit" name="login_button" value="Giriş yap">
          </td>
        </tr>
      </table>
      <a href="index.php" class="">Anasayfaya Dön</a>
      <?php
        if(isset($_POST["login_button"])) {
            require './Database.php';
            $login = false;
            $myEmail = $_POST["email"];
            $myPassword = $_POST["password"];
            try {
                //Burada kullanıcı girişi yapılıyor
                $query = 'SELECT mem_id, mem_firstname, mem_lastname, mem_photo, mem_tel, mem_email, mem_address, mem_date, mem_role FROM  bitkidb.member WHERE mem_email = :email AND mem_password = :password';
                $values = array(':email' => $myEmail, ':password' => $myPassword);
                try {
                    $res = $pdo->prepare($query);
                    $res->execute($values);
                } catch (PDOException $e) {
                    throw new Exception('Database query error - 1');
                }
                if ($res->rowCount()>0)
                {
                  while($row = $res->fetch()) {
                    $login = true;
                    $_SESSION["mem_id"] = $row[0];
                    $_SESSION["mem_firstname"] = $row[1];
                    $_SESSION["mem_lastname"] = $row[2];
                    $_SESSION["mem_photo"] = $row[3];
                    $_SESSION["mem_tel"] = $row[4];
                    $_SESSION["mem_email"] = $row[5];
                    $_SESSION["mem_address"] = $row[6];
                    $_SESSION["mem_date"] = $row[7];
                    $_SESSION["mem_role"] = $row[8];
                    $_SESSION["is_logged"] = true;
                    if($_SESSION["mem_role"] == "admin"){
                      header("Location: /bitkialisverissite/admin.php");
                    } else if($_SESSION["mem_role"] == "regular_member"){
                      header("Location: /bitkialisverissite/index_for_member.php");
                    } else if($_SESSION["mem_role"] == "store_owner_member"){
                      //burada store id yi getiriyorum
                      try {
                        $query = 'SELECT store.store_id FROM bitkidb.member, bitkidb.store WHERE store.mem_id = :member_id';
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
                            $_SESSION["store_id"] = $row[0];
                          }
                        }
                        unset($res);
                      } catch (Exception $e) {
                        echo "No store found";
                      }

                      //daha sonra index for member a yönlendiriyorum
                      header("Location: /bitkialisverissite/index_for_member.php");
                    }                   
                  }
                } else {
                  echo "No records matching your query were found.";
                  $_SESSION["is_logged"] = false;
                }

            } catch (Exception $e) {
                echo $e->getMessage();
                $_SESSION["is_logged"] = false;
                die();
            }
        }
        ?>
    </form>
    </div>
	</div>
</body>
</html>
