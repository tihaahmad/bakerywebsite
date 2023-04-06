<?php
	
	require_once "../dbconnection.php";
	
	require_once "../datamanage.php";
	$user = new User($connect);
	
	if($user->is_loggedin()) {
		
		$userdata = $user->finduserdata($_SESSION['user_session']);
		
		if(!empty($userdata['email'])) {
			
			$user_id = $userdata['id'];
			$user_name = $userdata['username'];
			$user_email = $userdata['email'];
	

		$wishlistData = $user->displayWishlist($user_id);

		// if(isset($_GET['addcart'])) {
		// 	$product = $user->getProductonce($_GET['addcart']);

		// 	if($user->wishlistAddcart($product['product_name'], $product['img_path'], $product['img_name'], 
		// 		$product['product_price'], $product['id'], $user_id)) {
		// 		$_SESSION['wishlist_add_cart'] = "Your product have added successfully..." ;
		// 		$user->redirect('user_wishlist.php');
		// 	} else {
		// 		$addFail = "Sorry, something went wrong with cart.";
		// 	}
		// }


		if(isset($_GET['wid'])) {
			$w_pid = $_GET['wid'];
			if($user->unlikeWishlist($w_pid, $user_id)) {
				if($user->deleteWishlist($w_pid, $user_id)) {
					$user->redirect('user_wishlist.php');
				} else {
					$fail_delete ="Sorry, something went wrong with deleting.";
				}
			} else {
				$fail_delete ="Sorry, something went wrong with deleting.";
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
	<title>Seabay</title>

	<?php include 'include/link.php'; ?>

</head>
<body> 

	<!--	++++++++++++++++++++++++++++++++++++++++++		NAVIGATION		++++++++++++++++++++++++++++++++++++++++++	-->
	<?php include 'include/header.php'; ?>

	<?php if(isset($fail_delete)) {
		echo "<div class='usetting_fail'> <i class='fas fa-times-circle'></i> <span>". 
		$fail_delete. ' </span> </div>';
		}
	?>


<form action="" method="post">
<div class="wishlist_con">

	<?php if(isset($_SESSION['wishlist_add_cart'])) {
		 echo '<div class="usetting_success"> <i class="fas fa-check-circle"></i> <span>'. 
		 $_SESSION["wishlist_add_cart"]. ' </span> 	</div>'.
		 '<a href="user_cart.php" class="wishlist_redirect">  Go cart  <i class="fa-solid fa-square-up-right"></i></a>';
		 unset($_SESSION['wishlist_add_cart']);
		}
	?>

	<?php if(isset($fail)) {
		echo "<div class='usetting_fail'> <i class='fas fa-times-circle'></i> <span>". 
		$fail. ' </span> </div>';
		}
	?>


	<h2> Wishlist 				<a href="../index.php"> BACK </a> </h2>

<!-- 	<div class="wishlist">
		<div class="wishlist_img">
			<img src="../img/defaultImg/product_default.jpg">
		</div>

		<div class="wishlist_content">
			<h3> Lorem Ipsum Lorem Ipsum Lorem Ipsum  Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum  </h3>
			<p> RM 00.00 </p>
			<a href=""> <i class="far fa-address-card"></i> View Seller </a>
			<button> <i class="fas fa-shopping-cart"></i> Add to Cart </button>
		</div>

		<div class="wishlist_remove">
			<a href="#"> remove </a>
		</div>

	</div> -->

	<?php

		if(isset($haventLogin) && isset($loginLink)) {
			echo 
			"<img class='cart_empty_img' src='../img/defaultImg/sorry.png' >
			<h3 class='cart_empty'>".$haventLogin."</h3> <a href='../others/user_login.php' class='go_shop'>".$loginLink."</a>";
		} else {

		$countWishlist = count($wishlistData);

		if($countWishlist < 1) {
		  echo
		  '<img class="cart_empty_img" src="../img/defaultImg/empty.png" >
		  <h3 class="cart_empty">Your Wishlist is empty.</h3>
			<a href="../index.php" class="go_shop"> Go Shopping Now</a>';
		}


		for ($i=0; $i < $countWishlist; $i++) { 
			$wid = $wishlistData[$i]['id'];
			$w_pname = $wishlistData[$i]['product_name'];
			$w_ppath = $wishlistData[$i]['product_path'];
			$w_pimg = $wishlistData[$i]['product_img'];
			$w_pprice = $wishlistData[$i]['price'];
			$w_pid = $wishlistData[$i]['pid'];


			echo 
			'<div class="wishlist">'.
				'<div class="wishlist_img">
					<img src="../'.$w_ppath.$w_pimg.'">
				</div>'.

				'<div class="wishlist_content">
					<h3> '.$w_pname.'  </h3>
					<p> RM '.$w_pprice.' </p>

					<a href="product_detail.php?pid='.$w_pid.'"> <i class="fa-solid fa-box-open"></i> View Product </a>
					<a href="seller_intro.php?pid='.$w_pid.'" class="wishlist_center"> <i class="far fa-address-card"></i> View Seller </a>
					<!-- <a href="user_wishlist.php?addcart='.$w_pid.'"> <i class="fas fa-shopping-cart"></i> Add to Cart </a> -->
				</div>'.

				'<div class="wishlist_remove">
					<!-- <button> remove </button> -->
					<a href="user_wishlist.php?wid='.$w_pid.'"> remove </a>
				</div>'.

			'</div>';
		}
	}	
	?>

</div>
</form>

	<?php include 'include/footer.php' ?>

</body>
</html>