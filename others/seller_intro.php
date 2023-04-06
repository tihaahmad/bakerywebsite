<?php
	
	require_once "../Dbh.php";
	
	require_once "../data.contr.php";
	$user = new User($connect);
	$admin = new Admin($connect);

		if(isset($_POST['logoutbtn'])) {
			if($user->logout()) {
				$user->redirect('../index.php');
			} else {
				echo 'Something error with logout class';
			}
		}


		$productDisplay = $user->displayProductcover();	//	display product thumbnail

		$allproduct = $user->displayAllProduct();	// fetch all product data


		$getproductonce = $user->getProductonce($_GET['pid']);
		$create_by = $getproductonce['create_by'];

		$sellerdata = $user->sellerIntro($create_by);
		$seller_id = $sellerdata['id'];
		$seller_uid = $sellerdata['user_id'];
		$seller_pnum = $sellerdata['phone_num'];
		$seller_address = $sellerdata['address'];
		$seller_bgpath = $sellerdata['bg_path'];
		$seller_bgname = $sellerdata['bg_name'];

		$seller_uinfo = $user->seller_uinfo($seller_uid);
		$seller_name = $seller_uinfo['username'];
		$seller_email = $seller_uinfo['email'];
		$seller_imgpath = $seller_uinfo['img_path'];
		$seller_imgname = $seller_uinfo['img_name'];


		$sellerProduct = $user->sellerintroProduct($seller_id);


		if(isset($_GET['pid']) && isset($_GET['id'])) {

			//	================	IS LOGIN 
			if($user->is_loggedin()) {
				
				$userdata = $user->finduserdata($_SESSION['user_session']);
				
				if(!empty($userdata['email'])) {
					
					$user_id = $userdata['id'];
					$user_name = $userdata['username'];
					$user_email = $userdata['email'];
					$status = $user->getwishlistStatus($_GET['pid'],$user_id);
					$wishlistdata = $user->getProductonce($_GET['pid']);
					$wishlist_pid = $_GET['pid'];
					$wishlist_name = $wishlistdata['product_name'];
					$wishlist_path = $wishlistdata['img_path'];
					$wishlist_img = $wishlistdata['img_name'];
					$wishlist_qty = $wishlistdata['product_quantity'];
					$wishlist_price = $wishlistdata['product_price'];


					if(empty($status)) {
						if($user->addwishlist($wishlist_name, $wishlist_path, $wishlist_img, $wishlist_price, $wishlist_pid, $user_id)) {
							if($user->likeWishlist($wishlist_pid, $user_id)) {
								$user->redirect('seller_intro.php?pid='.$wishlist_pid);
							} else {
								$wishlistfail = "Sorry, something went wrong with wishlist.";
							}
						} else {
							$wishlistfail = "Sorry, something went wrong with wishlist.";
						}
					} else if(!empty($status) && $status['action'] == 'like') {
						if($user->unlikeWishlist($wishlist_pid, $user_id)) {
							if($user->deleteWishlist($wishlist_pid, $user_id)) {
								$user->redirect('seller_intro.php?pid='.$wishlist_pid);
							} else {
								$unlikeFail = 'Sorry, something went wrong with likes.';
							}
						} else {
							$unlikeFail = 'Sorry, something went wrong with likes.';
						}
					} else if(!empty($status) && $status['action'] == 'unlike') {
						if($user->addwishlist($wishlist_name, $wishlist_path, $wishlist_img, $wishlist_price, $wishlist_pid, $user_id)) {
							if($user->likeWishlist($wishlist_pid, $user_id)) {
								$user->redirect('seller_intro.php?pid='.$wishlist_pid);
							} else {
								$wishlistfail = "Sorry, something went wrong with wishlist.";
							}
						} else {
							$wishlistfail = "Sorry, something went wrong with wishlist.";
						}
					}


				} else {
					$user->redirect('user_login.php');
				}		//	CHECK EMAIL EMPTY OR NOT
			} else {
					$user->redirect('user_login.php');
			}			//	CHECK USER LOGIN
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
	<?php include 'include/head.php'; ?>


	<?php if(isset($wishlistfail)) {
		echo "<div class='usetting_fail'> <i class='fas fa-times-circle'></i> <span>". 
		$wishlistfail. ' </span> </div>';
		}
	?>

	<?php if(isset($unlikeFail)) {
		echo "<div class='usetting_fail'> <i class='fas fa-times-circle'></i> <span>". 
		$unlikeFail. ' </span> </div>';
		}
	?>

	<div class="sellerintro_con">
		<div class="sellerintro">
			<div class="sellerintro_bg"> <img src="
				<?php 
					if(!empty($seller_bgpath) && !empty($seller_bgname)) {
						echo $seller_bgpath.$seller_bgname; 
						} else {
							echo "../img/defaultImg/bg_default.png";
						}
				?>
			"> </div>
			<div class="sellerintro_img_con">
				<div class="sellerintro_img"> <img src="

						<?php 
							if(!empty($seller_imgpath) && !empty($seller_imgname)) {
								echo "../".$seller_imgpath.$seller_imgname;
							} else {
								echo "../img/defaultImg/u_default.png";
							}
						 ?>

					" alt="Seller_profile_image"> </div>
			</div>


			<div class="sellerintro_name"> 
				<p><i class="fa-solid fa-store"></i> <?php echo $seller_name; ?> </p> 
				<p> <i class="fa-solid fa-phone"></i> <?php echo $seller_pnum ?> </p> 
				<p> <i class="fa-solid fa-envelope"></i> <?php echo $seller_email; ?> </p> 
			</div>
		</div>

<!-- 		<div class="sellerintro_right">
			<table>
				<tr>
					<th><i class="fa-solid fa-store"></i> Products :</th>
					<td> 177</td>
					<th></th>
					<td></td>
				</tr>
			</table>
		</div> -->

	</div>

	<h2 class="sellerintro_title"> <?php echo $seller_name;  ?> 's Products	<a href="product_detail.php?pid=<?php echo $_GET['pid']; ?> ">BACK</a></h2>

<?php
		// $productCount = count($sellerProduct);

		// for ($i=0; $i < $productCount; $i++) {
		// 	$productId = $sellerProduct[$i]['id'];
		// 	$productName = $sellerProduct[$i]['product_name'];
		// 	$productDir = $sellerProduct[$i]['img_path'];
		// 	$productCover = $sellerProduct[$i]['img_name'];
		// 	$productQty = $productDisplay[$i]['product_quantity'];
		// 	$productPrice = $sellerProduct[$i]['product_price'];
		// 	$productSeller = $sellerProduct[$i]['create_by'];

		// 	if($user->is_loggedin()) {
		// 		$userdata = $user->finduserdata($_SESSION['user_session']);
		// 		if(!empty($userdata['email'])) {
		// 			$user_id = $userdata['id'];
		// 			$status = $user->getwishlistStatus($productId,$user_id);
		// 		}
		// 	}

?>

	<div class="home_container1">
		<div class="home_container4" style="margin-top: 1% !important;">
				<?php

				$productCount = count($sellerProduct);

				for ($i=0; $i < $productCount; $i++) {
					$productId = $sellerProduct[$i]['id'];
					$productName = $sellerProduct[$i]['product_name'];
					$productDir = $sellerProduct[$i]['img_path'];
					$productCover = $sellerProduct[$i]['img_name'];
					$productQty = $productDisplay[$i]['product_quantity'];
					$productPrice = $sellerProduct[$i]['product_price'];
					$productSeller = $sellerProduct[$i]['create_by'];

					if($user->is_loggedin()) {
						$userdata = $user->finduserdata($_SESSION['user_session']);
						if(!empty($userdata['email'])) {
							$user_id = $userdata['id'];
							$status = $user->getwishlistStatus($productId,$user_id);
						}
					}

					if($productQty < 1) {

						echo "<div id=".$productId."> </div>";

						echo "<div class='home_sales cover_out_stock'> <a href='product_detail.php?pid=" .$productId. "'> " .
							"<div class='home_sales_img'> <img src='../".$productDir. $productCover."' alt='Product_" .$productId. "'/> </div>" .
							"<div>  <span> RM " .$productPrice.  "</span> " .
							"<p class='text'> " .$productName. " </p> " .
							"</div> </a>".
							"<div class='home_sales_option'>".
							"<a href='seller_intro.php?pid=".$productId."'>";
							 	if(empty($status)) {
							 		echo '<i class="fa-regular fa-heart"></i>';
							 	} else if(!empty($status) && $status['action'] == 'like') {
							 		echo '<i class="fa-solid fa-heart"></i>';
							 	} else if(!empty($status) && $status['action'] == 'unlike') {
							 		echo '<i class="fa-regular fa-heart"></i>';
							 	} else {
							 		echo '<i class="fa-regular fa-heart"></i>';
							 	}
							
							echo
							"</a></div>".
							"</div>";

					} else {

					echo "<div id=".$productId."> </div>";

					echo "<div class='home_sales'> <a href='product_detail.php?pid=" .$productId. "'> " .
						"<div class='home_sales_img'> <img src='../" .$productDir. $productCover. "' alt='Product_" .$productId. "'/> </div>" .
						"<div>  <span> RM " .$productPrice.  "</span> " .
						"<p class='text'> " .$productName. " </p> " .
						"</div> </a>".
						"<div class='home_sales_option'>".
						"<a href='seller_intro.php?pid=".$productId."'>";
						 	if(empty($status)) {
						 		echo '<i class="fa-regular fa-heart"></i>';
						 	} else if(!empty($status) && $status['action'] == 'like') {
						 		echo '<i class="fa-solid fa-heart"></i>';
						 	} else if(!empty($status) && $status['action'] == 'unlike') {
						 		echo '<i class="fa-regular fa-heart"></i>';
						 	} else {
						 		echo '<i class="fa-regular fa-heart"></i>';
						 	}
						
						echo
						"</a></div>".
						"</div>";
					}
				}


				?>
			
			</div> 	 <!--	CLOSE FOR SALES CONTAINER4	-->


	</div> 	 <!--	CLOSE FOR HOME CONTAINER1	-->
</div>
</div>


	<?php  include 'include/footer.php';  ?>

</body>

</html>

<?php

		// } else {
			// $user->redirect("../register.php");
		// } // if user_session didnt have email

	// } else {
		// $user->redirect("../register.php");
	// }	// if user not login

?>