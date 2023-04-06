<?php 
	require_once "../Dbh.php" ;
	
	require_once "../data.contr.php" ;
	$user = new User($connect) ;
	
	
	if($user->is_loggedin()) {
		
		$userdata = $user->finduserdata($_SESSION['user_session']);
		
		if(!empty($userdata['email'])) {
			
			// ++++++++++++++++++++++++++++		USER DATA		++++++++++++++++++++++++++++
			$user_id = $userdata['id'];
			$user_name = $userdata['username'];
			$user_email = $userdata['email'];
			$user_role = $userdata['user_role'];
			
		if($user_role == 2) {
			// ++++++++++++++++++++++++++++		SELLER DATA		++++++++++++++++++++++++++++
			$sellerdata = $user->findsellerid($user_id);
			$seller_id = $sellerdata['user_id'];
			
			if(!empty($seller_id)) {
				if($user_id == $seller_id) {
					$user->redirect('seller_page.php');
				}
			}
		}
			
		if(isset($_POST['seller_btn'])) {
			
			$seller_shop = $_POST['seller_shop'];
			$seller_email = trim($_POST['seller_email']);
			$seller_phone = trim($_POST['seller_phone'] );
			$seller_address = $_POST['seller_address'];
			
			if($seller_shop == ""){
				$error1 = "Please enter your alias.";
			} else if($seller_email == "") {
				$error2 = "Please enter your email.";
			} else if ($seller_phone == "") {
				$error3 = "Please enter your phone number.";
			} else if ($seller_address == "") {
				$error4 = "Please enter your address.";
			} else {
				if(!filter_var($seller_email,FILTER_VALIDATE_EMAIL)) {
					$error5 = "Please enter a valid email.";
				}
			
			try {
				if($user->seller_register($user_id, $seller_shop, $seller_email, $seller_phone, $seller_address, $user_name)) {
					if($user->user_role($user_id, $user_email)) {
						$registerSuccess = "Register Successfully.";
					}
				} else {
					$registerFail = "Sorry, something went wrong with register.";
				}
				
			} catch(PDOException $e) {
					echo $e->getMessage();
			}
			
			} // close for end function
			
		} // Close for submit button
  
?>

<!DOCTYPE html>
<html>
<head>
	<title>Register</title>

	<?php include 'include/homestyle.php'; ?>

<style type="text/css">

body {
	background: white !important;
}		

</style>

</head>
<body> 

	<!--	++++++++++++++++++++++++++++++++++++++++++		NAVIGATION		++++++++++++++++++++++++++++++++++++++++++	-->
	   <?php include 'include/header.php'; ?>
	   <header class="home">
		
		
	<!--	++++++++++++++++++++++++++++++++++++++++++		USER ICON		++++++++++++++++++++++++++++++++++++++++++	-->
	<img src="../img/logo.jpg" alt="seller icon" width="200" height="180" class="userIcon" />
		
	<table style="margin: 0 auto 15px;"><tr>
		<td style="width:29%"><hr/></td>
		<td class="signUp_title">Seller Registration</td>
		<td style="width:29%"><hr/></td>
	</tr></table>
		
	<?php if(isset($registerSuccess)) {
			echo "<div class='addProductSuccess'> <i class='fas fa-check-circle'></i> <span>". 
			$registerSuccess. " </span> </div>".
			"<a href='seller_page.php' class='sregister_redirect'> Seller page <i class='fa-solid fa-arrow-right'></i></a>	";
		}
	?>

	<?php if(isset($registerFail)) {
		echo "<div class='addProductFail'> <i class='fas fa-times-circle'></i> <span>". 
		$registerFail. ' </span> </div>';
		}
	?>
	
	<!--	++++++++++++++++++++++++++++++++++++++++++		REGISTER FORM		++++++++++++++++++++++++++++++++++++++++++	-->
	<form action="" method="post" class="sellerForm">

		<input type="text" name="seller_shop" placeholder="Please enter an alias" value="">
		<?php if(isset($error1)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error1 . " </span>"; } ?>

		<input type="email" name="seller_email" placeholder="&#xf0e0;   @gmail.com" value="">
		<?php if(isset($error2)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error2 . " </span>"; } ?>
		<?php if(isset($error5)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error5 . " </span>"; } ?>
      
		<input type="num" name="seller_phone" placeholder="&#xf095;   Exp.0123456789" value="" >
		<?php if(isset($error3)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error3 . " </span>"; } ?>

		<input type="text" name="seller_address" placeholder="&#xf041;   5,jalan botani 1/2..." value=""> 
		<?php if(isset($error4)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error4 . " </span>"; } ?>
		 
    <?php if(isset($success)) { echo '<span class="true"> &#xf0da; ' .$success. ' </span>'; } ?>
    
    <br/>
		<input type="submit" value="Submit" name="seller_btn" style="color: white; left: 18%;"> <br/>
		 
		<hr style="border: 1px solid #C5C5C5; width: 75%;"/>
		
		<div class="seller_back" >
		<p><a href="../index.php" > Back to Home</a></p>
		</div>
		
	</form>

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


<script>
        $(window).on("load",function(){
          $(".container").fadeOut("slow");
        });
</script>