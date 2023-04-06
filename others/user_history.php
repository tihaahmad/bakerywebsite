<?php
    
    require_once "../Dbh.php";
    
    require_once "../data.contr.php";
    $user = new User($connect);
    
    if($user->is_loggedin()) {
        
        $userdata = $user->finduserdata($_SESSION['user_session']);
        
        if(!empty($userdata['email'])) {
            
            $user_id = $userdata['id'];
            $user_name = $userdata['username'];
            $user_email = $userdata['email'];
            $user_address = $userdata['address'];


            //  ================    CART DATA / CART / CART FUNCTION
            $cart_data = $user->getCartdata($user_id);


            if(isset($_POST['logoutbtn'])) {
                if($user->logout()) {
                    $user->redirect('../register.php');
                } else {
                    echo 'Something error with logout class';
                }
            }


            //  ================    ORDER DATA 
            $getorderdata = $user->getorderdata($user_id);





    } else {
        $haventLogin = "Sorry, You haven't login.";
        $loginLink = "Go Login Now";
    }       //  CHECK EMAIL EMPTY OR NOT
} else {
    $haventLogin = "Sorry, You haven't login.";
    $loginLink = "Go Login Now";
}           //  CHECK IS LOGIN


?>

<!DOCTYPE html>
<html>
<head>
    <title>Android</title>

    <?php include 'include/homestyle.php'; ?>

</head>
<body> 

    <!--    ++++++++++++++++++++++++++++++++++++++++++      NAVIGATION      ++++++++++++++++++++++++++++++++++++++++++  -->
    <?php include 'include/header.php'; ?>

    <div class="wishlist_con" sytle="padding-bottom: 5% !important;">
      <h2> History         <a href="../index.php"> BACK </a> </h2>

      <?php
      if(isset($haventLogin) && isset($loginLink)) {
        echo 
        "<h3 class='cart_empty'>".$haventLogin."</h3> <a href='../others/user_login.php' class='go_shop'>".$loginLink."</a>";
      } else {


        $count_orders = count($getorderdata);
        
      if($count_orders > 0) {
      echo 
      '<div class="history_title_con">
        <div class="history_num"></div>'.

        '<div class="history_orderId">
          <span class="hist_left"> Order Id </span>
        </div>'.

        '<div class="history_purchaseQty">
          <span> Items </span>
        </div>'.

        '<div class="history_price">
          <span> Price </span>
        </div>'.

        '<div class="history_time">
          <span> Order Time </span>
        </div>'.

        '<div class="history_togglebtn">
        </div>'.

      '</div>';
        
      }

      if($count_orders < 1) {
        echo
        '<h3 class="cart_empty">Your History is empty.</h3>
        <a href="../index.php" class="go_shop"> Go Shopping Now</a>';
      }


      
      for ($i=0; $i < $count_orders; $i++) { 
        $order_id = $getorderdata[$i]['order_id'];
        $purchase_qty = $getorderdata[$i]['purchase_qty'];
        $order_price = $getorderdata[$i]['price'];
        $order_date = $getorderdata[$i]['order_time'];

        $x = $i + 1;

      if($count_orders > 0) {
        echo 
        '<div class="history_drop">'.
        '<div class="history_con">'.
          '<div class="history_con_number"> <p>'.$x.'</p> </div>
        <div class="history_num"><p> 1 </p></div>'.

        '<div class="history_orderId">
          <p> '.$order_id.'</p>
        </div>'.

        '<div class="history_purchaseQty">
          <p> '.$purchase_qty.' </p>
        </div>'.

        '<div class="history_price">
          <p> RM '.$order_price.' </p>
        </div>'.

        '<div class="history_time">
          <p> '.$order_date.' </p>
        </div>'.

        '<div class="history_togglebtn">
          <a href="#" data-toggle="dropdown"><i class="fas fa-caret-down fa_down"></i> </a>
        </div>'.

        '</div>'.

        '<div class="history_dt_con order-menu">';

         $historydata = $user->gethistorydata($user_id, $order_id);
         // $count_data = count($orderdata);
         foreach ($historydata as $c => $value) {

         $pid = $historydata[$c]['pid']; 
         $product_path = $historydata[$c]['img_path'];
         $product_img = $historydata[$c]['img_name'];
         $product_name = $historydata[$c]['product_name'];
         $product_qty = $historydata[$c]['quantity'];
         $product_price = $historydata[$c]['price'];

         // echo $product_name.'/ '.$product_path.$product_img. '/ '. $product_name. '/ '.$product_qty. '/ '. $product_price.'<br>';

         echo 
         '<div class="history_dt_small">'.
         '<div class="history_dt_img"><img src="../'.$product_path.$product_img.'"></div>'.
         '<div class="resop">'.
         '<div class="history_dt_name"><p> '.$product_name.' </p></div>'.
         '<div class="history_dt_qty"><p> <span class="history_bold">Qty :</span>  '.$product_qty.' </p></div>'.
         '<div class="history_dt_price"><p> <span class="history_bold">Total price :</span> RM '.$product_price.' </p></div>'.
         '</div>'.
         '<div class="history_dt_btn"><a href="product_detail.php?pid='.$pid.'">Buy Again</a></div>'.
         '</div>';

         }

         echo '</div>'.
         '</div>';
      }
    }



 /*      <div class="history_drop">
      <div class="history_con">
        <div class="history_con_number"> <p>1</p> </div>
        <div class="history_num"><p> 1 </p></div>

        <div class="history_orderId">
          <p>dddddddddd</p>
        </div>

        <div class="history_purchaseQty">
          <p> 2 </p>
        </div>

        <div class="history_price">
          <p> RM 300 </p>
        </div>

        <div class="history_time">
          <p> 2022-02-23 13:06:50 </p>
        </div>

        <div class="history_togglebtn">
          <a href="#" data-toggle="dropdown"><i class="fas fa-caret-down fa_down"></i> </a>
        </div>

      </div>
      <div class="history_dt_con order-menu">
        <div class="history_dt_small">
        <div class="history_dt_img"><img src="../img/defaultImg/product_default.jpg"></div>
        <div class="history_dt_name"><p>User Name is ??? </p></div>
        <div class="history_dt_qty"><p> <span class="history_bold">Qty :</span>  2 </p></div>
        <div class="history_dt_price"><p> <span class="history_bold">Price :</span> RM 360.00 </p></div>
        <div class="history_dt_btn"><a href="#">Buy Again</a></div>
        </div>
      </div>

       </div> --> */

    echo 
    '</div>';

      }

      ?>

    <?php include 'include/footer.php'; ?>

    <script  src="../js/history.js"></script>

</body>

</html>
