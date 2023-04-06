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

      $getorderid = $user->getorderid($user_id);
      $order_id = $getorderid['order_id'];
      $orderdata = $user->thankyouData($order_id);
      $purchase_qty = $orderdata['purchase_qty'];
      $price = $orderdata['price'];
      $time = $orderdata['order_time'];

      $item_detail = $user->gethistorydata($user_id, $order_id);
      $sum = $user->sumhistoryPrice($order_id, $user_id);
      $subtotal = $sum['subtotal'];
      $subtotal = number_format($subtotal, 2);

?>

<!DOCTYPE html>
<html>
<head>
	
	<title> Thank you ! </title>
  <link rel="stylesheet" type="text/css" href="../css/thankyou.css">
	<?php include 'include/homestyle.php'; ?>

<style>



</style>
</head>
<body> 

	<?php include 'include/header.php'; ?>

<div class="thankyou">
<div class="text_description">
	<p> Thanks for your purchased !</p>
  <div class="table">
    <div class="flex-table">
      <div class="table_left">
        <p>Order Id</p>
      </div>

      <div class="table_right">
        <p> <?php echo $order_id; ?> </p>
      </div>
    </div>

    <div class="drop_con">
    <div class="flex-table">
      <div class="table_left">
        <p>You have purchased</p>
      </div>

      <div class="table_right">
        <a href="#" class="drop" data-toggle="item_toggle">
          <p id="p">
            <span><?php echo $purchase_qty; ?> </span> items
            <i class="fa-solid fa-caret-down fa_down"></i>
          </p>
        </a>
      </div>
    </div>

    <div class="table_center">
      <?php
        foreach ($item_detail as $i => $value) {
          $item_name = $value['product_name'];
          $item_qty = $value['quantity'];
          $item_price = $value['price'];
          $fee = $item_price * 0.05;
          $shipping = number_format($fee, 2);
          

          $x = $i + 1;

          echo 
          '<div class="item_cont">'.
            '<p class="a">'.$x.'</p>'.
            '<p class="b">'.$item_name.'</p>'.
            '<p class="c"><span class="qty_blue">Qty: </span>'.$item_qty.'</p>'.
            '<p class="d">RM '.$item_price.'</p>'.
          '</div>';
        }
      ?>
    </div>


    <div class="flex-table table_last">
      <div class="table_left">
        <p>Subtotal</p>
      </div>

      <div class="table_right">
        <p> RM <?php echo $subtotal; ?> </p>
      </div>
    </div>

    <div class="flex-table">
      <div class="table_left">
        <p>Shipping Fee</p>
      </div>

      <div class="table_right">
        <p> RM <?php if(!empty($shipping)) { echo $shipping;} else { echo "0";} ?> </p>
      </div>
    </div>


    <div class="flex-table table_totalp">
      <div class="table_left">
        <p>Total Price</p>
      </div>

      <div class="table_right">
        <p> RM <?php echo $price; ?> </p>
      </div>
    </div>




  </div>
	<a href="../index.php"> Home </a>
</div>

</div>

    <script src="../js/thankyou.js"></script>

</body>

</html>

<?php

	} else {
		$user->redirect("../index.php");
	} // if user_session didnt have email

} else {
	$user->redirect("../index.php");
}	// if user not login

?>