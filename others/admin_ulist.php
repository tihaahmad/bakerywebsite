<?php
  

require_once "../Dbh.php";

require_once "../data.contr.php";
$user = new User($connect);
$admin = new Admin($connect);


if($user->is_loggedin()) { 

  
  $userdata = $user->finduserdata($_SESSION['user_session']);

  if(!empty($userdata['email'])) {

    if($userdata['user_role'] == 3) {

      $displayData = $admin->displayAlluser();

?>

<!DOCTYPE html>
<html>
<head>
  <title>Netify</title>

  <?php include 'include/homestyle.php'; ?>

</head>
<body> 

  
<!--  ++++++++++++++++++++++++++++++++++++++++++    NAVIGATION    ++++++++++++++++++++++++++++++++++++++++++  -->
  
  <?php include 'include/adminNav.php'; ?>

  <h2 class="admin_ulist_title"> Users </h2>
  <div class="admin_container2">
    <div class="admin_ulist_con">

      <div class="admin_ulist_title">
        <div class="admin_ulist_photo">
          <p> Photo </p>
        </div>

        <div class="admin_ulist_name">
          <p> Username </p>
        </div>

        <div class="admin_ulist_phone">
          <p> Phone Number </p>
        </div>

        <div class="admin_ulist_email">
          <p> Email </p>
        </div>

        <div class="admin_ulist_address">
          <p> Address </p>
        </div>
      </div>

      <?php 

        foreach($displayData as $value) {
          $id = $value['id'];
          $photo = $value['img_path'].$value['img_name'];
          $name = $value['username'];
          $phone = $value['phone_num'];
          $email = $value['email'];
          $address = $value['address'];

          echo
          '<div class="admin_ulist">
            <div class="admin_ulist_photo">';
              if($photo == NULL) {           
                echo '<img src="../img/defaultImg/u_default.png" alt="User_'.$id.'">';
              } else {
                echo '<img src="../'.$photo.'" alt="User_'.$id.'">';
              }
            
            echo
            '</div>'.

            '<div class="admin_ulist_name">
              <p> '.$name.' </p>
            </div>'.

            '<div class="admin_ulist_phone">';
              if($photo == NULL) {
                echo ' <p style="font-style: italic;"> Null </p>';
              } else {
                echo '<p> '.$phone.' </p>';
              }
            
            echo
            '</div>'.

            '<div class="admin_ulist_email">
              <p> '.$email.' </p>
            </div>'.

            '<div class="admin_ulist_address">';
              if($address == NULL) {
                echo ' <p style="font-style: italic;"> Null </p>';
              } else {
                echo '<p> '.$address.' </p>';
              }
              
            echo
            '</div>'.
          '</div>';
        }

      ?>

      <div class="admin_ulist">
        <div class="admin_ulist_photo">
          <img src="../img/defaultImg/product_default.jpg">
        </div>

        <div class="admin_ulist_name">
          <p> User name </p>
        </div>

        <div class="admin_ulist_phone">
          <p> 0123456789 </p>
        </div>

        <div class="admin_ulist_email">
          <p> xxxx@gmail.com </p>
        </div>

        <div class="admin_ulist_address">
          <p> sdhfjsahfassdjdjkhdskkdsj </p>
        </div>
      </div>


    </div>  
  </div>
  </div>

  
</body>
</html>

<?php
    } else {
      $user->redirect('../index.php');
    }

  } else {
    $user->redirect('../index.php');
  }
  
} else {
  $user->redirect('../index,php');
}
?>