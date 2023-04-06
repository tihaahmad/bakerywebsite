<?php
  
  require_once "../Dbh.php";
  
  require_once "../data.contr.php";
  $user = new User($connect);
  
  if($user->is_loggedin()) {
    
    $userdata = $user->finduserdata($_SESSION['user_session']);
    
    if(!empty($userdata['email'])) {
      
      // ++++++++++++++++++++++++++++   USER DATA   ++++++++++++++++++++++++++++
      $user_id = $userdata['id'];
      $user_email = $userdata['email'];
      $user_role = $userdata['user_role'];
      $user_name = $userdata['username'];
      $user_imgPath = $userdata['img_path'];
      $user_imgName = $userdata['img_name'];

      if($user_role == 2 || $user_role == 3) {

        $sellerdata = $user->findsellerid($user_id);

        if(!empty ($sellerdata['id'])) {

          $seller_id = $sellerdata['id'];
          $getProduct = $user->addedProduct($seller_id);
          $seller_email = $sellerdata['email'];
          $seller_shop = $sellerdata['shop_name'];
          $seller_pnum = $sellerdata['phone_num'];
          $seller_address = $sellerdata['address'];
          $seller_bgPath = $sellerdata['bg_path'];
          $seller_bgName = $sellerdata['bg_name'];

          $getdata = $user->sellergetdata($seller_id);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Android</title>

    <link type="text/css" rel="stylesheet" href="../css/cart-style.css" />
  <link rel="stylesheet" type="text/css" href="../css/slideshow.css" />
  <link rel="stylesheet" type="text/css" href="../css/navbar.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css" />

</head>
<body> 

    <!--  ++++++++++++++++++++++++++++++++++++++++++    NAVIGATION    ++++++++++++++++++++++++++++++++++++++++++  -->
    <?php include 'include/sellerNav.php'; ?>


  <div class="sellerNav" id="SellerNav">
    <a href="#about" class="seller_panel_img" class="btn" >  <img src="

    <?php
      if(!empty($user_imgPath) && !empty($user_imgName)) {
        echo "../".$user_imgPath. $user_imgName ;
      } else {
        echo "../img/userGallery/pngtree-user-vector-avatar-png-image_1541962.jpg";
      }
    ?>
      " alt="Seller_Profile_Image" width="100%"> </a>
    <p> <?php echo $user_name; ?> </p>

    <a href="seller_setting.php" class="btn " > <i class="fas fa-user"></i>  Account</a>
    <a href="seller_page.php" class="btn " >  <i class="fas fa-box"></i>  Products</a>
    <a href="seller_order.php" class="btn sellerNav_active" > <i class="fas fa-clipboard-list"></i>  Orders</a>

    <a href="../index.php" class="admin_back"> <i class="fas fa-chevron-circle-left"></i>Back</a>

  </div>
  <!-- ================  END NAVIGATION  ================ -->


    <div class="seller_product_container2">
      <span class="sorder_title"> Orders <i class="fa-solid fa-clipboard-list"></i> </span>

      <?php

      $count_data = count($getdata);

      if($count_data < 1) {
        echo
        '<img class="cart_empty_img" src="../img/defaultImg/history2.png" style="width: 300px">
        <h3 class="cart_empty">Ops, your product list is empty.</h3>
        <a href="../index.php" class="go_shop"> Add product</a>';
      }


      for ($d=0; $d < $count_data; $d++) {
        $id = $getdata[$d]['id'];
        $name = $getdata[$d]['product_name'];
        $path = $getdata[$d]['img_path'];
        $img = $getdata[$d]['img_name'];
        $qty = $getdata[$d]['quantity'];
        $price = $getdata[$d]['price'];
        $orderid = $getdata[$d]['order_id'];
        $uid = $getdata[$d]['add_by'];
        $time = $getdata[$d]['update_time'];

        $sum = $qty * $price;
        $total = number_format($sum,2);

        $number = $d + 1;

        $userdata = $user->finduserdata($uid);
        $uname = $userdata['username'];
        $upath = $userdata['img_path'];
        $uimg = $userdata['img_name'];
        $ufull = $userdata['full_name'];
        $uphone = $userdata['phone_num'];
        $uaddress = $userdata['address'];
        
        echo 
        '<div class="sorder_user">'.
          '<p> Order By : <span> '.$uname.' </span> <img src="../';

          if(!empty($upath) && !empty($uimg)) {
            echo $upath.$uimg;
          } else {
            echo 'img/defaultImg/u_default.png';
          }

          echo
          '"> </p>'.
          '<p class="sorderid"> Order ID : <span> '.$orderid.' </span></p>'.
          '<p class="sorderid"> Order Time : <span> '.$time.' </span></p>'.
        '</div>'.
        '<div class="sorder_udetail">
          <p> Receiver Name : <span> '.$ufull.' </span></p>
          <p> Phone Number : <span> '.$uphone.' </span></p>
          <p> Delivery Address : <span> '.$uaddress.' </span></p>
        </div>'.
        '<div class="sorder_con">'.
          '<div class="sorder_num_con"> <p> '.$number.' </p> </div>
          <div class="sorder_num"> <p> 1 </p> </div>
          <div class="sorder_id"> <img src="../'.$path.$img.'"> </div>
          <div class="sorder_qty"> <p> '.$name.' </p> </div>
          <div class="sorder_price" style="width: 7%;"> <p> <span>Quantity:  </span> '.$qty.' </p> </div>
          <div class="sorder_price"> <p> <span>Total Amount:  </span> RM '.$total.' </p> </div>'.
        '</div>';

      }

      ?>



<!--       <div class="sorder_small_con sorder_menu">
        <div class="sorder_small">
          <div class="sorder_small_img"> <img src="../img/defaultImg/product_default.jpg"></div>
          <div class="sorder_small_id"> <p> <span> pid : </span> 1 </p> </div>
          <div class="sorder_small_name"> <p> PREORDER GBF Valentine Card Granblue Fantasy </p> </div>
          <div class="sorder_small_qty"> <p><span>Qty :</span> 2 </p> </div>
          <div class="sorder_small_price"> <p><span>Total : </span> RM 00.00 </p> </div>
        </div>

        <div class="sorder_small">
          <div class="sorder_small_img"> <img src="../img/defaultImg/product_default.jpg"></div>
          <div class="sorder_small_id"> <p> <span> pid : </span> 1 </p> </div>
          <div class="sorder_small_name"> <p> PREORDER GBF Valentine Card Granblue Fantasy </p> </div>
          <div class="sorder_small_qty"> <p><span>Qty :</span> 2 </p> </div>
          <div class="sorder_small_price"> <p><span>Total : </span> RM 00.00 </p> </div>
        </div>
      </div>
      </div> -->
      
    </div>




    <script  src="../js/seller_orders.js"></script>

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
      $user->redirect("../index.php");
    } // if user_session didnt have email

  } else {
    $user->redirect("../index.php");
  } // if user not login

?>