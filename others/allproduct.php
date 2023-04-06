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


		$slide = $admin->findslide();			/*	DISPLAY LANDING SLIDE	*/
		$cat = $admin->findCat();					/*	DISPLAY CATEGORIES 		*/


		$productDisplay = $user->displayProductcover();

		$allproduct = $user->displayAllProduct();


		if(isset($_GET['pid'])) {

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
								$user->redirect('allproduct.php#'.$wishlist_pid);
							} else {
								$wishlistfail = "Sorry, something went wrong with wishlist.";
							}
						} else {
							$wishlistfail = "Sorry, something went wrong with wishlist.";
						}
					} else if(!empty($status) && $status['action'] == 'like') {
						if($user->unlikeWishlist($wishlist_pid, $user_id)) {
							if($user->deleteWishlist($wishlist_pid, $user_id)) {
								$user->redirect('allproduct.php#'.$wishlist_pid);
							} else {
								$unlikeFail = 'Sorry, something went wrong with likes.';
							}
						} else {
							$unlikeFail = 'Sorry, something went wrong with likes.';
						}
					} else if(!empty($status) && $status['action'] == 'unlike') {
						if($user->addwishlist($wishlist_name, $wishlist_path, $wishlist_img, $wishlist_price, $wishlist_pid, $user_id)) {
							if($user->likeWishlist($wishlist_pid, $user_id)) {
								$user->redirect('allproduct.php#'.$wishlist_pid);
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

		// foreach($slide as $key => $value) {
		// 	if($slide[$key]['used_for'] == 'slide') {
		// 		echo "<img src='" .$slide[$key]['img_path']. $slide[$key]['img_name']."' width='100px;' >";
		// 	}
		// }
?>

<!DOCTYPE html>
<html>
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
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Bakery Website</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/css/lightgallery.min.css">

    <link rel="stylesheet" href="../css/style.css">
</head>
<body> 

	    <!-- header -->

    <header class="header">

        <a href="#" class="logo"> <i class="fas fa-bread-slice"></i> bakery </a>

        <nav class="navbar">
            <a href="#home">home</a>
            <a href="#about">about</a>
            <a href="others/allproduct.php">product</a>
            <a href="#gallery">gallery</a>
            <a href="#team">team</a>
            <a href="#review">review</a>
            <a href="#order">order</a>
        </nav>


        <div class="icons">
            Hi <?php echo $user_name;?>
            <a href="user_cart.php"><div id="cart-btn" class="fas fa-shopping-cart"></div></a>
            <!--<div id="menu-btn" class="fas fa-bars"></div>-->
        </div>

    </header>

    <!-- header end -->

	
    <!-- product -->

    <section class="product" id="product">

        <h1 class="heading">our <span> products</span></h1>

        <div class="box-container">

            <div class="box">
                <div class="image">
                    <img src="../images/product-1.jpg" alt="">
                </div>
                <div class="content">
                    <h3>apple pie</h3>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <span class="price">$15.99</span>
                    <a href="product/product-detail.html" class="btn">add to cart</a>
                </div>
            </div>

            <div class="box">
                <div class="image">
                    <img src="../images/product-2.jpg" alt="">
                </div>
                <div class="content">
                    <h3>apple pie</h3>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <span class="price">$18.99</span>
                    <a href="#" class="btn">add to cart</a>
                </div>
            </div>
<?php
					$productCount = count($allproduct);

					for ($i=0; $i < $productCount; $i++) {
						$productId = $allproduct[$i]['id'];
						$productName = $allproduct[$i]['product_name'];
						$productDir = $allproduct[$i]['img_path'];
						$productCover = $allproduct[$i]['img_name'];
						$productPrice = $allproduct[$i]['product_price'];
						$productSeller = $allproduct[$i]['create_by'];

						if($user->is_loggedin()) {
							$userdata = $user->finduserdata($_SESSION['user_session']);
							if(!empty($userdata['email'])) {
								$user_id = $userdata['id'];
								$status = $user->getwishlistStatus($productId,$user_id);
							}
						}
						?>
            <div class="box">

            	

                <div class="image">
                    <img src="../<?php echo $productDir . $productCover;?>" alt="">
                </div>
                <div class="content">
                    <h3><?php echo $productName;?></h3>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="price">RM<?php echo $productPrice; ?></span>
                    <a href="product_detail.php?pid=<?php echo $productId?>" class="btn">add to cart</a>
                </div>
            </div>
        <?php }
        ?>

            <div class="box">
                <div class="image">
                    <img src="../images/product-4.jpg" alt="">
                </div>
                <div class="content">
                    <h3>apple pie</h3>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <span class="price">$15.99</span>
                    <a href="#" class="btn">add to cart</a>
                </div>
            </div>

            <div class="box">
                <div class="image">
                    <img src="../images/product-5.jpg" alt="">
                </div>
                <div class="content">
                    <h3>apple pie</h3>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="price">$27.99</span>
                    <a href="#" class="btn">add to cart</a>
                </div>
            </div>

            <div class="box">
                <div class="image">
                    <img src="../images/product-6.jpg" alt="">
                </div>
                <div class="content">
                    <h3>apple pie</h3>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <span class="price">$25.99</span>
                    <a href="#" class="btn">add to cart</a>
                </div>
            </div>

        </div>

    </section>


    <!-- product end-->

	
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