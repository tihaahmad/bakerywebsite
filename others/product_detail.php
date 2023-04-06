<?php
	
	require_once "../Dbh.php";
	
	require_once "../data.contr.php";
	$user = new User($connect);

		//	================	PRODUCT DATA
		if(isset($_GET['pid'])) {
			$productDetail = $user->productDetail($_GET['pid']);

			$productId = $productDetail['id'];
			$productName = $productDetail['product_name'];
			$productPrice = $productDetail['product_price'];
			$productQty = $productDetail['product_quantity'];
			$productCat = $productDetail['category_type'];
			$product_createby = $productDetail['create_by'];
			$product_coverpath = $productDetail['img_path'];
			$product_covername = $productDetail['img_name'];

			$productImg = $user->productImage($productId);
			// echo var_dump($productImg);

			$sellerintro = $user->sellerIntro($product_createby);
			$seller_id = $sellerintro['id'];
			$seller_uid = $sellerintro['user_id'];
			$seller_shop = $sellerintro['shop_name'];

			$seller_uinfo = $user->seller_uinfo($seller_uid);
			$seller_name = $seller_uinfo['username'];
			$seller_imgpath = $seller_uinfo['img_path'];
			$seller_imgname = $seller_uinfo['img_name'];

			

		// ================  ADD TO CART BUTTON
		if(isset($_POST['add_cart'])) {

		//	================	IS LOGIN 
		if($user->is_loggedin()) {
			
			$userdata = $user->finduserdata($_SESSION['user_session']);
			
			if(!empty($userdata['email'])) {
				$bool = false;
				
				$user_id = $userdata['id'];
				$user_name = $userdata['username'];
				$user_email = $userdata['email'];
				$pquantity = $_POST['quantity'];


			if($productQty > 0) {
				$cart_data = $user->getCartdata($user_id);
				foreach ($cart_data as $value) {
					$checking = $value['pid'];

					if($productId == $checking) {
						$bool = true;
					// //	================	ADD TO CART
					// if($user->addtoCart($productId, $seller_id, $productName, $product_coverpath, $product_covername, $pquantity, $productPrice, $user_id)) {
					// 	$_SESSION['add_cart_success'] = "Your product have added successfully..." ;
					// 	// $link = '../others/product_detail.php?pid='
					// 	$user->redirect('../others/product_detail.php?pid='.$_GET['pid']) ;
					// } else {
					// 	$fail = "Sorry, something went wrong with add product.";
					// }

					}
				}
				if($bool) {
					$fail = "Sorry, This item has already added to cart.";
				} else {
					//	================	ADD TO CART
					if($user->addtoCart($productId, $seller_id, $productName, $product_coverpath, $product_covername, $pquantity, $productPrice, $user_id)) {
						$_SESSION['add_cart_success'] = "Your product have added successfully..." ;
						// $link = '../others/product_detail.php?pid='
						$user->redirect('../others/product_detail.php?pid='.$_GET['pid']) ;
					} else {
						$fail = "Sorry, something went wrong with add product.";
					}
				}
			} else {
				$fail = "Sorry, This item is out of stock.";
			}




			} else {
				$user->redirect('login.php');
			}		//	CHECK EMAIL EMPTY OR NOT
		} else {
				$user->redirect('user_login.php');
		}			//	CHECK USER LOGIN

		}		//	SUBMIT BUTTON




		// ================  BUY NOW BUTTON
		if(isset($_POST['buy_now'])) {

		//	================	IS LOGIN 
		if($user->is_loggedin()) {
			
			$userdata = $user->finduserdata($_SESSION['user_session']);
			
			if(!empty($userdata['email'])) {
				$bool = false;
				
				$user_id = $userdata['id'];
				$user_name = $userdata['username'];
				$user_email = $userdata['email'];
				$pquantity = $_POST['quantity'];


			if($productQty > 0) {
				$cart_data = $user->getCartdata($user_id);
				foreach ($cart_data as $value) {
					$checking = $value['pid'];

					if($productId == $checking) {
						$bool = true;
					// //	================	ADD TO CART
					// if($user->addtoCart($productId, $seller_id, $productName, $product_coverpath, $product_covername, $pquantity, $productPrice, $user_id)) {
					// 	$_SESSION['add_cart_success'] = "Your product have added successfully..." ;
					// 	// $link = '../others/product_detail.php?pid='
					// 	$user->redirect('../others/product_detail.php?pid='.$_GET['pid']) ;
					// } else {
					// 	$fail = "Sorry, something went wrong with add product.";
					// }

					}
				}
				if($bool) {
					$fail = "Sorry, This item has already added to cart.";
				} else {
					//	================	ADD TO CART
					if($user->addtoCart($productId, $seller_id, $productName, $product_coverpath, $product_covername, $pquantity, $productPrice, $user_id)) {
						$user->redirect('../others/user_cart.php') ;
					} else {
						$fail = "Sorry, something went wrong with add product.";
					}
				}
			} else {
				$fail = "Sorry, This item is out of stock.";
			}




			} else {
				$user->redirect('user_login.php');
			}		//	CHECK EMAIL EMPTY OR NOT
		} else {
				$user->redirect('user_login.php');
		}			//	CHECK USER LOGIN

		}		//	SUBMIT BUTTON



		//	================	ADD TO WISHLIST
		if(isset($_POST['wishlist'])) {

			//	================	IS LOGIN 
			if($user->is_loggedin()) {
				
				$userdata = $user->finduserdata($_SESSION['user_session']);
				
				if(!empty($userdata['email'])) {
					
					$user_id = $userdata['id'];
					$user_name = $userdata['username'];
					$user_email = $userdata['email'];
					$pquantity = $_POST['quantity'];
					$status = $user->getwishlistStatus($productId,$user_id);

				if(empty($status)) {
					if($user->addwishlist($productName, $product_coverpath, $product_covername, $productPrice, $productId, $user_id)) {
						if($user->likeWishlist($productId, $user_id)) {
							$user->redirect('../others/product_detail.php?pid='.$_GET['pid']);	
						} else {
							$wishlistfail = "Sorry, something went wrong with wishlist.";
						}
					} else {
						$wishlistfail = "Sorry, something went wrong with wishlist.";
					}

				} else if(!empty($status) && $status['action'] == 'like') {
					if($user->unlikeWishlist($productId, $user_id)) {
						if($user->deleteWishlist($productId, $user_id)) {
							$user->redirect('../others/product_detail.php?pid='.$_GET['pid']);	
						} else {
							$unlikeFail = 'Sorry, something went wrong with likes.';
						}
					} else {
						$unlikeFail = 'Sorry, something went wrong with likes.';
					}
				} else if(!empty($status) && $status['action'] == 'unlike') {
					if($user->addwishlist($productName, $product_coverpath, $product_covername, $productPrice, $productId, $user_id)) {
						if($user->likeWishlist($productId, $user_id)) {
							$user->redirect('../others/product_detail.php?pid='.$_GET['pid']);	
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


		if($user->is_loggedin()) {
			$userdata = $user->finduserdata($_SESSION['user_session']);
			if(!empty($userdata['email'])) {
				$user_id = $userdata['id'];
				$status = $user->getwishlistStatus($productId,$user_id);

				//	================	LOGOUT 
				if(isset($_POST['logoutbtn'])) {
					if($user->logout()) {
						$user->redirect('../index.php');
					} else {
						echo 'Something error with logout class';
					}
				}
			}
		}

?>


<!DOCTYPE html>
<html>
  <head>
  	<?php include('include/homestyle.php');?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Product Card/Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Bakery Website</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/css/lightgallery.min.css">

    <link rel="stylesheet" href="../product/style.css">
  </head>
  <body>
    <?php 
if($user->is_loggedin()) {

        $userdata = $user->finduserdata($_SESSION['user_session']);

        if(!empty($userdata['email'])) {
        
        $welcome = "Hi, ";
        $user_setting = "<a href=user_setting.php ><span class=dropdown_icon>&#10148; </span>  User Setting </a>";
        $history = "<a href=user_history.php ><span class=dropdown_icon>&#10148; </span>  History </a>";
        $logout = "<a href=../index.php?logout=true class=logout><span class=dropdown_icon>&#10148; </span>  Logout <i class='fa-solid fa-right-from-bracket'></i> </a>";

        $user_id = $userdata['id'];
        $user_role = $userdata['user_role'];
        $user_name = $userdata['username'];
        $user_imgPath = $userdata['img_path'];
        $user_imgName = $userdata['img_name'];

        if($user_role == 3) {
            $admin_panel = "<a href=admin_panel.php ><span class=dropdown_icon>&#10148; </span>  Admin Panel </a>";
        }

        if($user_role == 2 || $user_role == 3) {
            $isSeller = "Seller";
        } else {
            $isNotseller = "Register as Seller";
        }
        
        if(isset($_GET['logout'])) {
            if($user->logout()) {
                $user->redirect('../index.php');
            } else {
                echo 'Something error with logout class';
            }
        }

        }

    } else {
        
        $register = "<a href=user_register.php ><span class=dropdown_icon>&#10148; </span>  Register </a>";
        $noLogin = "<a href=user_login.php ><span class=dropdown_icon>&#10148; </span>  Login </a>";
        $pleaseSignin = "Hi, Sign In ? ";
        $isNotseller = "Register as Seller";
        
    }
    ?>
    <!-- header -->

    <header class="header">

        <a href="#" class="logo"> <i class="fas fa-bread-slice"></i> bakery </a>

        <nav class="navbar">
        	<?php echo $user_name;?>
            <a href="#home">home</a>
            <a href="#about">about</a>
            <a href="#product">product</a>
            <a href="#gallery">gallery</a>
            <a href="#team">team</a>
            <a href="#review">review</a>
            <a href="#order">order</a>
        </nav>

        <div class="icons">
            <div id="cart-btn" class="fas fa-shopping-cart"></div>
            <div id="menu-btn" class="fas fa-bars"></div>
        </div>

    </header>

    <!-- header end -->
    
    <?php if(isset($_SESSION['add_cart_success'])) {
		 echo '<div class="usetting_success"> <i class="fas fa-check-circle"></i> <span>'. 
		 $_SESSION["add_cart_success"]. ' </span> 	</div>'.
		 "<div class='usetting_sucess_redirect'>
		 <a href='../index.php' class='usetting_success_back'> <i class='fas fa-arrow-left'></i> Home</a>
		 <a href='user_cart.php' class='redirect_back_cart'> Cart <i class='fa-solid fa-arrow-up-right-from-square'></i></a>
		 </div>";
		 unset($_SESSION['add_cart_success']);
		}
	?>

	<?php if(isset($fail)) {
		echo "<div class='usetting_fail'> <i class='fas fa-times-circle'></i> <span>". 
		$fail. ' </span> </div>';
		}
	?>

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
    <div class = "card-wrapper">
      <div class = "card">
        <!-- card left -->
        <?php 
			$allProduct = count($productImg);

			for ($i=0; $i < $allProduct; $i++) {
				
				$productImg_id = $productImg[$i]['id'];
				$productImg_path = $productImg[$i]['img_path'];
				$productImg_name = $productImg[$i]['img_name'];
				$productImg_pid = $productImg[$i]['product_id'];

				$x = $i + 1;

				echo 	"<div class='product_slide'>" .
				"<img src='" .$productImg_path. $productImg_name. "' alt='Product_Images' style='width:100%' /> </div>";

			}
		}
		?>
        <div class = "product-imgs">
          <div class = "img-display">
            <div class = "img-showcase">
              <img src = "../images/<?php echo $product_covername;?>">
            </div>
          </div>
        </div>

        <!-- card right -->
        <div class = "product-content">
          <h2 class = "product-title"><?php echo $productName; ?></h2>
          <a href = "#" class = "product-link">visit bakery store</a>
          <div class = "product-rating">
            <i class = "fas fa-star"></i>
            <i class = "fas fa-star"></i>
            <i class = "fas fa-star"></i>
            <i class = "fas fa-star"></i>
            <i class = "fas fa-star-half-alt"></i>
          </div>

          <div class = "product-price">
           <h2>RM <?php echo $productPrice; ?></h2>
          </div>

          <div class = "product-detail">
            <h2>about this item: </h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo eveniet veniam tempora fuga tenetur placeat sapiente architecto illum soluta consequuntur, aspernatur quidem at sequi ipsa!</p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur, perferendis eius. Dignissimos, labore suscipit. Unde.</p>
            <ul>
              <li>Color: <span>Black</span></li>
              <li>Available: <span>in stock</span></li>
              <li>Category: <span>Shoes</span></li>
              <li>Shipping Area: <span>All over the world</span></li>
              <li>Shipping Fee: <span>Free</span></li>
            </ul>
          </div>

         <form action="" method="post" class="product_submit">
		<table class="product_table">
		
			<tr>
				<th> Price 		</th>
				<td colspan="3"> RM <?php echo $productPrice; ?> </td>
			</tr>


			
<!-- 			<tr>
				<th> Shipping		 <i class="fas fa-truck"></i></th>
				<td colspan="3"> Shipping to </td>
			</tr>
			
			<tr>
				<th>  </th>
				<td colspan="3"> Shipping fee </td>
			</tr>
			
			<tr>
				<th> Color </th>
				<td colspan="3" style="padding-left: 0;"> <div class="product_color"> 
					<span> Yellow </span> 
					<span> Red </span>
					<span> Blue </span> 
					<span> Green </span> 
					<span> Navy Blue </span> 
				</div> </td>
			</tr>
			
			<tr>
				<th> Type </th>
				<td colspan="3" style="padding-left: 0;"> <div class="product_type"> 
					<span> Game only </span> 
					<span> Game + All unlock </span> 
				</div> </td>
			</tr> -->

			<tr>
				<th> In Stock   </th>
				<?php 

				if($productQty < 1) {
					echo '<td colspan="3" class="pdetail_out_stock">'. $productQty . '</td>';

				} else {
					echo '<td colspan="3">'.$productQty.'</td>'; 
				}
				
				?>
			</tr>

			<?php
				if($productQty < 1) {
				echo
				'<tr>
					<td colspan="3" class="pdetail_out_stock" style="padding-top: -5%;"> <i class="fa-solid fa-circle-exclamation"></i> Sorry, this item is out of stock. </td>
				</tr>';
				}
			?>
			
		


			

		</table>
				<input type="number" name="quantity" value="1">
				<input type="submit" name="add_cart" value="Add Cart" class="product_addCart" />
				<input type="submit" name="buy_now" value="Buy Now" />
				<button class="btn_heart" type="submit" name="wishlist" href="#"> 

					<?php 
						if(empty($status)) {
							echo '<i class="fa-regular fa-heart"></i>';
						} else if(!empty($status) && $status['action'] == 'like') {
							echo '<i class="fa-solid fa-heart"></i>';
						} else if(!empty($status) && $status['action'] == 'unlike') {
							echo '<i class="fa-regular fa-heart"></i>';
						} else {
							echo '<i class="fa-regular fa-heart"></i>';
						}
					?>
				</button>
			</form>


          <div class = "social-links">
            <p>Share At: </p>
            <a href = "#">
              <i class = "fab fa-facebook-f"></i>
            </a>
            <a href = "#">
              <i class = "fab fa-twitter"></i>
            </a>
            <a href = "#">
              <i class = "fab fa-instagram"></i>
            </a>
            <a href = "#">
              <i class = "fab fa-whatsapp"></i>
            </a>
            <a href = "#">
              <i class = "fab fa-pinterest"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

     <!-- footer -->

    <section class="footer">

        <div class="box-container">

            <div class="box">
                <h3>address</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Alias sit debitis.</p>
                <div class="share">
                    <a href="#" class="fab fa-facebook-f"></a>
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-instagram"></a>
                    <a href="#" class="fab fa-linkedin"></a>
                </div>
            </div>

            <div class="box">
                <h3>E-mail</h3>
                <a href="#" class="link">yiyun1002@gmail.com</a>
                <a href="#" class="link">yiyun02@gmail.com</a>
            </div>

            <div class="box">
                <h3>call us</h3>
                <p>+61 (2) 1478 2369</p>
                <p>+61 (2) 1478 2369</p>
            </div>

            <div class="box">
                <h3> opening hours</h3>
                <p>Monday - Friday: 9:00 - 23:00 <br> Saturday: 8:00 - 24:00 </p>
            </div>

        </div>

        <div class="credit">created by <span>yiyun</span> all rights reserved! </div>

    </section>







    <!-- footer ends -->

    
    <script src="script.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/js/lightgallery.min.js"></script>

    <script src="js/script.js"></script>

    <script>
        lightGallery(document.querySelector('.gallery .gallery-container'));
    </script>

  </body>
</html>