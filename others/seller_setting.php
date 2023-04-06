<?php
	
	require_once "../Dbh.php";
	
	require_once "../data.contr.php";
	$user = new User($connect);
	$admin = new Admin($connect);
	
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

				if(!empty($sellerdata['id']) && !empty($sellerdata['email'])) {

					$seller_id = $sellerdata['id'];
					$seller_email = $sellerdata['email'];
					$seller_shop = $sellerdata['shop_name'];
					$seller_pnum = $sellerdata['phone_num'];
					$seller_address = $sellerdata['address'];
					$seller_bgPath = $sellerdata['bg_path'];
					$seller_bgName = $sellerdata['bg_name'];

					$bgdir = '../img/sellerbg/';

					if(!file_exists($bgdir)) {
						mkdir($bgdir);
					}


					if(isset($_POST['seller_update'])) {

						$sup_shop = $_POST['sup_shop'];
						$sup_pnum = $_POST['sup_pnum'];
						$sup_address = $_POST['sup_address'];

						$fileName = $_FILES['file']['name'] ;
						$fileType = $_FILES['file']['type'] ;
						$fileSize = $_FILES['file']['size'] ;
						$fileTmp = $_FILES['file']['tmp_name'] ;

						if(empty($sup_shop)) {
							$err1 = "Please enter your shop name.";
						} else if(empty($sup_pnum)) {
							$err2 = "Please enter your phone number.";
						} else if(empty($sup_address)) {
							$err3 = "Please enter your address.";
						} else {

							if(!empty($fileName) && !empty($fileType) && !empty($fileSize) && !empty($fileTmp)) {
								if($admin->fileChecking($bgdir, $fileName, $fileSize, $fileType, $fileTmp) == 1) {
								  $bgerr[] = "Sorry, your file cannot bigger than 15MB.";
								} else if($admin->fileChecking($bgdir, $fileName, $fileSize, $fileType, $fileTmp) == 2) {
								  $bgerr[] = "Sorry, your file only accept jpg, jpeg or png.";
								} else {

									if($user->seller_setting($seller_id, $seller_email, $sup_shop, $sup_pnum, $sup_address, $bgdir, $fileName, $user_name)) {
									  // unlink("../".$user_imgPath.$user_imgName) ; 
										$_SESSION['seller_update_success'] = "Your information was updated successfully..." ;
										$user->redirect('../others/seller_setting.php') ;

								} else {
									$err4 = "Sorry, something went wrong with the update.";
								}
							}		//	END CHECKING BACKGROUND FILE 

						} else {
								$bgerr[] = "Please insert a background image.";
							}
						}		//	END CHECKING EMPTY FIELD
					}		//	SUBMIT BUTTON

?>


<!DOCTYPE html>
<html>
<head>
	<title> Seller Settings </title>

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

  <a href="seller_setting.php" class="btn sellerNav_active" > <i class="fas fa-user"></i>  Account</a>
  <a href="seller_page.php" class="btn " >  <i class="fas fa-box"></i>  Products</a>
  <a href="seller_order.php" class="btn" > <i class="fas fa-clipboard-list"></i>    Orders</a>

  <a href="../index.php" class="admin_back"> <i class="fas fa-chevron-circle-left"></i>Back</a>

</div>
<!-- ================  END NAVIGATION  ================ -->


	

	<div class="setting-container">
	<span class="setting-title"> Seller profile </span>

	<?php if(isset($_SESSION['seller_update_success'])) {
		 echo '<div class="usetting_success"> <i class="fas fa-check-circle"></i> <span>'. 
		 $_SESSION["seller_update_success"]. ' </span> '.
		 "<a href='seller_page.php' class='usetting_success_back'> <i class='fas fa-arrow-left'></i> Back</a>	</div>";
		 unset($_SESSION['seller_update_success']);
		}
	?>

	<?php if(isset($err4)) {
		echo "<div class='usetting_fail'> <i class='fas fa-times-circle'></i> <span>". 
		$err4. ' </span> </div>';
		}
	?>

	<form action="" method="post" class="sSetting_con" enctype="multipart/form-data">
		<div class="sSetting-form">

		<div class="sSetting-boxes">
			<label for="sup_shop">Shop Name </label><br/>
			<input type="text" name="sup_shop" placeholder="  Enter your shop name" value="<?php echo $seller_shop; ?>">
			<?php if(isset($err1)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $err1 . " </span>"; } ?>
		</div>


		<div class="sSetting-boxes">
			<label for="sup_pnum">Phone number </label><br/>
			<input type="number" name="sup_pnum" placeholder="&#xf095;  Exp.0123456789" value="<?php echo $seller_pnum; ?>">
			<?php if(isset($err2)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $err2 . " </span>"; } ?>
		</div>	

		
		<div class="sSetting-boxes">
			<label for="sup_address">Address </label><br/>
			<input type="text" name="sup_address" placeholder="&#xf041;  5,jalan test 1/2..." value="<?php echo $seller_address; ?>">
			<?php if(isset($err3)) { echo "<span class='usetting_false'> <i class='fa-solid fa-circle-exclamation'></i> " . $err3 . " </span>"; } ?>
		</div>	


		<div class="seller_bg"> 
			<div class="seller_bg_con">

			  <div class="seller_bg_img">

			    <div class="filePreview"> 
			    	<img id="filePreview" name="up_image" src=" 

			    	<?php
			    		if(!empty($seller_bgPath) && !empty($seller_bgName)) {
			    			echo $seller_bgPath. $seller_bgName ;
			    		} else {
			    			echo "../img/defaultImg/bg_default2.jpg";
			    		}
			    	?>

			    	 ">
			    </div>
			      <label for="file">Add an background</label>
			      <input type="file" id="file" name="file" accept="media/*" onchange="showPreview(event);">
			      <small> File extension: .jpg, .png </small>
			  </div>

			  <?php  if(isset($bgerr)) {
			    foreach($bgerr as $err) {
			      echo "<span class='usetting_false'> <i class='fa-solid fa-circle-exclamation'></i>  " .$err. "</span>";
			    }
			  }  ?>
			</div>
		</div>
	
		<input type="submit" value="Save" name="seller_update">

	</div>		<!-- SETTING-FORM	-->

	</form>
	<a href="../index.php"> Back </a>
	</div>

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

<script type="text/javascript">
  
    function showPreview(event){
  if(event.target.files.length > 0){
    var src = URL.createObjectURL(event.target.files[0]);
    var preview = document.getElementById("filePreview");
    preview.src = src;
    preview.style.display = "block";
  }
}

</script>