<?php
	
	require_once "../Dbh.php";
	
	require_once "../data.contr.php";
	$user = new User($connect);
	
	if($user->is_loggedin()) {
		
		$userdata = $user->finduserdata($_SESSION['user_session']);
		
		if(!empty($userdata['email'])) {
			
			// ++++++++++++++++++++++++++++		USER DATA		++++++++++++++++++++++++++++
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


					if(isset($_GET['pid'])) {
						if($user->deleteproduct($_GET['pid'], $seller_id)) {
								$user->redirect('seller_page.php');
							}
					}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Seller</title>
	
	<link type="text/css" rel="stylesheet" href="../css/cart-style.css" />
	<link rel="stylesheet" type="text/css" href="../css/slideshow.css" />
	<link rel="stylesheet" type="text/css" href="../css/navbar.css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
	<link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css" />


</head>
<body> 

	<!--	++++++++++++++++++++++++++++++++++++++++++		NAVIGATION		++++++++++++++++++++++++++++++++++++++++++	-->
	<?php include 'include/sellerNav.php'; ?>


	<div class="sellerNav" id="SellerNav">
  <a href="#about" class="seller_panel_img" class="btn" >  <img src="

  <?php
  	if(!empty($user_imgPath) && !empty($user_imgName)) {
  		echo "../".$user_imgPath. $user_imgName ;
  	} else {
  		echo "../img/defaultImg/u_default.png";
  	}
  ?>
  	" alt="Seller_Profile_Image" width="100%"> </a>
  <p> <?php echo $user_name; ?> </p>

  <a href="seller_setting.php" class="btn " > <i class="fas fa-user"></i>  Account</a>
  <a href="seller_page.php" class="btn sellerNav_active" >  <i class="fas fa-box"></i>  Products</a>
  <a href="seller_order.php" class="btn" > <i class="fas fa-clipboard-list"></i>  Orders</a>

  <a href="../index.php" class="admin_back"> <i class="fas fa-chevron-circle-left"></i>Back</a>

</div>
<!-- ================  END NAVIGATION  ================ -->



<div class="seller_product_container1">
  <h2 class="seller_panel_title">  Products List 		<i class="fas fa-boxes"></i>	 </h2>


  <div class="seller_add_product">
  	<a href="seller_addProd.php"> Add Product 		<i class="far fa-plus-square"></i> </a>
  </div>


  <hr class="seller_panel_hr">

<!--     <table class="cart_container2">
      <tr>
        <th class="cart-product-th"> Product </th>
				<th class="cart-space-th">  </th>
        <th class="cart-price-th"> In Stock </th>
        <th class="cart-quantity-th"> Price </th>
        <th class="cart-total-th"> Edit </th>
        <th class="cart-remove-th"> Delete </th>
      </tr>
    </table> -->
     <?php
    	 $countSP = count($getProduct);

    	 if($countSP < 1) {
    	   echo
    	   '<img class="cart_empty_img" src="../img/defaultImg/history2.png" style="width: 300px">
    	   <h3 class="cart_empty">Ops, your product list is empty.</h3>
    	 	<a href="../index.php" class="go_shop"> Add product</a>';
    	 }

      
      if($countSP > 0) {
      echo '
      <div class="seller_cart_title">
    		<div class="sellercart_num_title"><p>  </p></div>
    	
        <div class="sellercart_img "><p>Product</p> </div>
        
        <div class="sellercart_des_title ">
          <p>Name</p> </div>
    		
    		<div class="sellercart_type ">
    		<p>  </p> </div>
        
        <div class="sellercart_stock ">
          <p> In Stock </p> </div>

        <div class="sellercart_stock " style="margin-left: 3.5%;">
          <p>Price</p> </div>
      
<!--         <div class="sellercart_total seller_product_edit ">
          <p>Edit</p> </div> -->
      
        <div class="sellercart_remove ">
          <p>Delete</p> </div>
      
      </div>';

      }
  
  //		=====================   ADDED PRODUCTS BY SELLER



	 for ($i=0; $i < $countSP; $i++) { 
	 	$SP_id = $getProduct[$i]['id'];
	 	$SP_name = $getProduct[$i]['product_name'];
	 	$SP_path = $getProduct[$i]['img_path'];
	 	$SP_imgname = $getProduct[$i]['img_name'];
	 	$SP_quantity = $getProduct[$i]['product_quantity'];
	 	$SP_cat = $getProduct[$i]['category_type'];
	 	$SP_price = $getProduct[$i]['product_price'];

	 	$x = $i + 1;

	echo 	
  '<div class="seller_cart">'.
		'<div class="sellercart_num"><p> ' .$x. ' </p></div>'.
	
    '<div class="sellercart_img"><img src=" ../' .$SP_path. $SP_imgname. ' " alt="Products_' .$x. '"/> </div>'.
    
    '<div class="sellercart_des">
      <p>' .$SP_name. '</p> </div>'.
		
		'<div class="sellercart_type">
		<p>  </p> </div>'.
    
    '<div class="sellercart_stock">
      <p> ' .$SP_quantity. ' </p> </div>'.

    '<div class="sellercart_stock" style="margin-left: 3.5%;">
      <p>' .$SP_price. '</p> </div>'.
  
    '<!-- <div class="sellercart_total seller_product_edit">
      <p><a href=""> <i class="fas fa-edit fa-edit-large"></i> </a></p> </div> -->'.
  
    '<div class="sellercart_remove">
      <a href="seller_page.php?pid='.$SP_id.'">&#215;</a> </div>'.
  
  '</div>' ;
	}
?>

	
</div> 	 <!--	close for HOME CONTAINER1	-->
	

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
	}	// if user not login

?>

<!-- *******************		PRELOADER		*******************	 -->
<script type="text/javascript">
  // Get the container element
  var btnContainer = document.getElementById("SellerNav");

  // Get all buttons with class="btn" inside the container
  var btns = btnContainer.getElementsByClassName("btn");

  // Loop through the buttons and add the active class to the current/clicked button
  for (var i = 0; i < btns.length; i++) {
    btns[i].addEventListener("click", function() {
      var current = document.getElementsByClassName("sellerNav_active");
      current[0].className = current[0].className.replace(" sellerNav_active", "");
      this.className += " sellerNav_active";
    });
  }
</script>