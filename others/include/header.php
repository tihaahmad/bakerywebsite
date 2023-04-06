<?php
	
	require_once "../Dbh.php";
	
	require_once "../data.contr.php";
	$user = new User($connect);
	
	if($user->is_loggedin()) {
		
		$userdata = $user->finduserdata($_SESSION['user_session']);
		
		if(!empty($userdata['email'])) {
			
			$user_id = $userdata['id'];
			$user_name = $userdata['username'];
			$user_fullname = $userdata['full_name'];
			$user_email = $userdata['email'];
			$user_phone = $userdata['phone_num'];
			$user_address = $userdata['address'];


			//	================	CART DATA / CART / CART FUNCTION
			$cart_data = $user->getCartdata($user_id);


			//	================	SUM PRODUCT / SUM  / SUBTOTAL
			$sum = $user->sumSubtotal($user_id);
			$subtotal = $sum['subtotal'];
			$subtotalresult = number_format($subtotal, 2);
			$total = $subtotal + ($subtotal * 0.05);
			$totalresult = number_format($total, 2);


			if(isset($_POST['logoutbtn'])) {
				if($user->logout()) {
					$user->redirect('../index.php');
				} else {
					echo 'Something error with logout class';
				}
			}


			if(isset($_GET['deletecid']) && isset($_GET['cid'])) {
				if($user->deleteCart($_GET['cid'], $user_id)) {
						$user->redirect('user_cart.php');
					}
			}



			if(isset($_POST['action'])) {
				if(empty($user_fullname)) {
					$empty_name = '<i class="fa-solid fa-circle-exclamation" style="margin-right: 2%;"></i>';
				} else if(empty($user_phone)) {
					$empty_phone = '<i class="fa-solid fa-circle-exclamation" style="margin-right: 2%;"></i>';
				} else if(empty($user_address)) {
					$empty_address = '<i class="fa-solid fa-circle-exclamation" style="margin-right: 2%;"></i>';
				} else {	
					$user->redirect('user_payment.php');
				}
			}




	} else {
		$haventLogin = "Sorry, You haven't login.";
		$loginLink = "Go Login Now";
	}		//	CHECK EMAIL EMPTY OR NOT
} else {
	$haventLogin = "Sorry, You haven't login.";
	$loginLink = "Go Login Now";
}			// 	CHECK IS LOGIN


?>

<!DOCTYPE html>
<html>
<head>
	<title>Shopping Cart UI</title>
	<link rel="stylesheet" type="text/css" href="../css/cart-style.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,900" rel="stylesheet">
</head>
<body>
	<form method="post" action="">

		<?php
	if(isset($haventLogin) && isset($loginLink)) {
		echo 
		"<h3 class='cart_empty'>".$haventLogin."</h3> <a href='../others/user_login.php' class='go_shop'>".$loginLink."</a>";

	} else {


		$cart_total = count($cart_data);

		if($cart_total > 0) { ?>

   <div class="CartContainer">
   	   <div class="Header">
   	   	<h3 class="Heading">Shopping Cart</h3>
   	   	<h5 class="Action">Remove all</h5>
   	   </div>

   	   <div class="Cart-Items">
   	   <?php }
   	   ?>

   	   <?php 
   	   for ($i=0; $i < $cart_total; $i++) { 
    	if($cart_total > 0 ) {

    		$cart_id = $cart_data[$i]['id'];
    		$p_name = $cart_data[$i]['product_name'];
    		$p_path = $cart_data[$i]['img_path'];
    		$p_img = $cart_data[$i]['img_name'];
    		$p_quantity = $cart_data[$i]['quantity'];
    		$p_price = $cart_data[$i]['price'];
    		$p_id = $cart_data[$i]['pid'];
    		$add_by = $cart_data[$i]['add_by'];

    		$totalXprice = $p_quantity * $p_price;
    		$p_total = number_format($totalXprice, 2);

    		$x = $i + 1;


    		$getQty = $user->getProductonce($p_id);
    		$qty = $getQty['product_quantity'];


    		if($qty < 1 || $p_quantity > $qty) {
    			$user->cartPending($p_id, $user_id);
    			?>
   	   	  <div class="image-box">
   	   	  	<img src="../<?php echo $p_path . $p_img; ?>" style={{ height="120px" }} />
   	   	  </div>
   	   	  <div class="about">
   	   	  	<h1 class="title"><?php echo $p_name; ?></h1>
   	   	  </div>
   	   	  <div class="quantity">
   	   	  	<div class="title"><?php echo $p_quantity; ?></div>
   	   	  </div>
   	   	  <div class="prices">
   	   	  	<div class="amount"><?php echo $p_total?></div>
   	   	  	<div class="save"><u>Save for later</u></div>
   	   	  	<div class="remove">
   	   	  		<button type="submit" name="delete<?php echo $i?>"></button>
   	   	  		<a href="user_cart.php?deletecid=<?php echo $i;?> &cid=<?php echo $card_id;?>"></a>
   	   	  		<input type="hidden" value="'.$i.'" name="remove">
   	   	  </div>
   	   </div>
<?php 

	} else {
    		$array[] = $cart_id;
    		foreach ($array as $value) {
    			$user->cartCheckout($value, $user_id);
    		}

  			echo
  			//	SHOPPING CART / CART
  		  '<div class="shopping_cart">
  				<div class="cart-num"><p> '.$x.' </p></div>
  				
  			   <div class="cart-img"><img src="../'.$p_path.$p_img.'" alt="Electronic Products"/> </div>
  			    
  			    <div class="cart-description">
  			      <p> '.$p_name.' </p>
  			    </div>
  			    
  					<div class="cart-type">
  						<p>  </p>
  					</div>
  			    
  			    <div class="cart-price">
  			      <p> RM '.$p_price.' </p>
  			    </div>'.

  			   '<div class="cart-quantity">
  			      <p> '.$p_quantity.' </p>
  			   </div>'.
  			  
  			   '<div class="cart-total">
  			     <p> RM '.$p_total.' </p>
  			   </div>'.
  			  
  			   '<div class="cart-remove">
  			    <!--  <button type=submit name="delete'.$i.'">&#215;</button>	-->
  			     <a href="user_cart.php?deletecid='.$i.'&cid='.$cart_id.'"> &#215; </a>
  			     <input type="hidden" value="'.$i.'" name="remove">
  			   </div>'.
  					
  			  
  			  '</div>'; 	 //close for CART CONTAINER

    		}  //	CHECK ITEM QTY EMPTY
		}
	}


		if($cart_total <= 0) {
 			echo 
 			'<h3 class="cart_empty">Your Shopping cart is empty.</h3>
  		<a href="../index.php" class="go_shop"> Go Shopping Now</a>';
  	}




  	if($cart_total > 0) {
  		echo 
    '<div class="checkout_container">
    	<div class="checkout_flex">

    <table class="change_info_con">';


    if(!empty($user_fullname)) {
    	echo
    	'<tr>
    		<th style="text-align:left;"> Receiver full name :</th>
    		<td style="text-align:left; color: var(--blue); font-weight: bold;"> '.$user_fullname.'</td>
    	</tr>';

    
    } else {
    		echo
    		'<tr>
    			<th style="text-align:left;"> Receiver full name :</th>
    			<td style="text-align:left; color: #d9534f; font-weight: bold;">'; 

    			if(isset($empty_name)) {
    				echo $empty_name;
    			}

    			echo
    			'Sorry, you didnt have a full name.</td>
    		</tr>';

    }

    if(!empty($user_phone)) {
    	echo
    	'<tr>
    		<th style="text-align:left;"> Receiver phone :</th>
    		<td style="text-align:left; color: var(--blue); font-weight: bold;"> '.$user_phone.'</td>
    	</tr>';
    
    } else {
    		echo
    		'<tr>
    			<th style="text-align:left;"> Receiver phone :</th>
    			<td style="text-align:left; color: #d9534f; font-weight: bold;">'; 

    			if(isset($empty_phone)) {
    				echo $empty_phone;
    			}

    			echo
    			'Sorry, you didnt have a phone number.</td>
    		</tr>';

    }


    if(!empty($user_address)) {
    	echo
    	'<tr>
    		<th style="text-align:left;"> Delivery Address :</th>
    		<td style="text-align:left; color: var(--blue); font-weight: bold;"> '.$user_address.'</td>
    	</tr>'.

    	'<tr>
    		<th></th>
    		<td class="change_info"><a href="user_setting.php"> Change information </a></td>
    	</tr>';

    
    } else {
    		echo
    		'<tr>
    			<th style="text-align:left;"> Delivery Address :</th>
    			<td style="text-align:left; color: #d9534f; font-weight: bold;">'; 

    			if(isset($empty_address)) {
    				echo $empty_address;
    			}

    			echo
    			'Sorry, you didnt have an address.</td>
    		</tr>'.

    		'<tr>
    			<th></th>
    			<td style="text-align: left; font-weight: bold; padding-top: 4%;"><a href="user_setting.php"> Add information </a></td>
    		</tr>';

    }

    echo
    	'<table class="total_table">
    		<tr>
    			<th> Sub total :</th>
    			<td> RM '.$subtotalresult.' </td>
    		</tr>'.

    		'<tr>
    			<th> Shipping :</th>
    			<td> 5% </td>
    		</tr>'.

    		'<tr>
    			<th> Grand total :</th>
    			<td style="color: var(--blue); font-weight: bold;"> RM '.$totalresult.'</td>
    		</tr>'.

    	'</table></div>'.
   	 		'<button href="#" type="submit" name="action"> Checkout <i class="fas fa-arrow-right right_arrow"></i></button>
    </div>';}

    }


?>
   	   
   	 <hr> 
<!--    	 <div class="checkout">
   	 <div class="total">
   	 	<div>
   	 		<div class="Subtotal">Sub-Total</div>
   	 		<div class="items">2 items</div>
   	 	</div>
   	 	<div class="total-amount">$6.18</div>
   	 </div>
   	 <button class="button">Checkout</button></div> -->
   </div>
</form>
</body>
</html>