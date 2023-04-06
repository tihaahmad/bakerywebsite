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

		$getCategoryid = $admin->getCategoryid($_GET['category']);

		$filterCategory = $admin->filterCategory($getCategoryid['category_name']);
		$count = count($filterCategory);



?>

<!DOCTYPE html>
<html>
<head>
	<title>Android</title>

	<?php include 'include/cart.php'; ?>

</head>
<body> 

	<!--	++++++++++++++++++++++++++++++++++++++++++		NAVIGATION		++++++++++++++++++++++++++++++++++++++++++	-->
	<?php include 'include/head.php'; ?>

	<div class="ucateogry_slot">
		<h3> Others Categories </h3>
		<?php
			foreach ($cat as $value) {
				$cat_id = $value['id'];
				$cat_name = $value['category_name'];
				$cat_path = $value['category_path'];
				$cat_img = $value['category_file'];

				echo 
				'<a href="user_category_page.php?category='.$cat_id.'" >
				<img src="../'.$cat_path.$cat_img.'" class="ucateogry_slot_thumbnail"> '.$cat_name.
				'</a>';
			}
		?>
	</div>

	<div class="ucategory mb-2">
		<h2 class="ucategory_title">Category:  <span><?php 
			echo "<img src=../".$getCategoryid['category_path'].$getCategoryid['category_file'].">";
			echo $getCategoryid['category_name']. ' ('.$count.' Items)'; 
		?> </span> </h2>
		<a href="../index.php"> BACK </a>
	</div>

	<div class="allproduct_con">

<!-- 		<div class="allproduct_leftside">
			<a href="#"> All ()</a>
			<a href="#"> Electronic products ()</a>
			<a href="#"> Computer accessories ()</a>
			<a href="#"> Man clothes ()</a>
			<a href="#"> Woman clothes ()</a>
		</div> -->

		<div class="allproduct_rightside">
						<!--		TEST		-->
			<div class="allproduct_rightside_con">

					<?php

					if($count < 1) {
						echo
						'<div class="empty_category_con">
						<h3 class="category_empty">Sorry, there are no items in this category...</h3>
						<a href="../index.php" class="category_back"> BACK </a>
						</div>';
					}

					$productCount = count($filterCategory);

					for ($i=0; $i < $productCount; $i++) {
						$productId = $filterCategory[$i]['id'];
						$productName = $filterCategory[$i]['product_name'];
						$productDir = $filterCategory[$i]['img_path'];
						$productCover = $filterCategory[$i]['img_name'];
						$productQty = $filterCategory[$i]['product_quantity'];
						$productPrice = $filterCategory[$i]['product_price'];
						$productSeller = $filterCategory[$i]['create_by'];

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
								"<div class='home_sales_img'> <img src='../".$productDir. $productCover. "' alt='Product_" .$productId. "'/> </div>" .
								"<div>  <span> RM " .$productPrice.  "</span> " .
								"<p class='text'> " .$productName. " </p> " .
								"</div> </a>".
								"<div class='home_sales_option'>".
								"<a href='index.php?pid=".$productId."'>";
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

						echo "<div class='home_sales'> <a href='product_detail.php?pid=" .$productId. "'> " .
							"<div class='home_sales_img'> <img src=' ../" .$productDir. $productCover. "' alt='Product_" .$productId. "'/> </div>" .
							"<div>  <span> RM " .$productPrice.  "</span> " .
							"<p class='text'> " .$productName. " </p> " .
							"</div> </a>";
							// "<div class='home_sales_option'>".
							// "<a href='user_category_page.php?pid=".$productId."'>";
							//  	if(empty($status)) {
							//  		echo '<i class="fa-regular fa-heart"></i>';
							//  	} else if(!empty($status) && $status['action'] == 'like') {
							//  		echo '<i class="fa-solid fa-heart"></i>';
							//  	} else if(!empty($status) && $status['action'] == 'unlike') {
							//  		echo '<i class="fa-regular fa-heart"></i>';
							//  	} else {
							//  		echo '<i class="fa-regular fa-heart"></i>';
							//  	}

							//  echo '</a></div>';
							echo "</div>";
						}	
					}

					?>
			<!-- 		<div class="home_sales">
						<a href="others/product_detai.php" >
							<img src="img/stock/animal_crossing.jpg" alt="Computer accessories"/>
							<div>
								<span> RM236.00 </span>
								<p class="text"> [iOS] Animal crossing pocket camp </p>
							</div>
						</a>
					</div>

					</div>			 -->
					<!--		TEST		-->	
				
				</div> 	 <!--	CLOSE FOR SALES CONTAINER4	-->
		</div>

	</div> 	 <!--	CLOSE FOR HOME CONTAINER1	-->
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