<?php 
	require_once "../Dbh.php" ;
	
	require_once "../data.contr.php" ;
	$user = new User($connect) ;
	
	
	if($user->is_loggedin()) {
		
		$userdata = $user->finduserdata($_SESSION['user_session']);
		
		if(!empty($userdata['email'])) {
			
			$user_id = $userdata['id'];
			$user_email = $userdata['email'];
			$user_role = $userdata['user_role'];		

		if($user_role == 3 ) {
			$user->redirect('seller_page.php');
		}	
		
    if($user_role == 2) {
			$sellerdata = $user->findsellerid($user_id);
			
		if(!empty($sellerdata['user_id'])) {
			$seller_uid = $sellerdata['user_id'];
        
			if($user_id == $seller_uid) {
					$user->redirect('seller_page.php');
				}
			}
		} else {
    
		if(isset($_POST['start_seller'])) {
      $user->redirect('seller_register.php');
      
		} 
    } 

?>

<!DOCTYPE html>
<html>
<head>
	<title>Seller Registration</title>

	<link type="text/css" rel="stylesheet" href="../css/cart-style.css" />
	<link rel="stylesheet" type="text/css" href="../css/slideshow.css" />
	<link rel="stylesheet" type="text/css" href="../css/navbar.css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
	<link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css" />

	<style type="text/css">
		body {
			background: white !important;
		}
	</style>

</head>
<body> 
	   <?php include 'include/head.php'; ?>
	   <header class="home">
		<img src="../img/logo.jpg" alt="seller icon" width="200" height="180" class="userIcon" />
		
	<p class="start_seller_title"> Welcome to <span class="start_seller_blue">Netify</span> </p>
	<p class="start_seller_content"> To start uploading electronic product<br/> your information. </p>
		
	
	<!--	++++++++++++++++++++++++++++++++++++++++++		REGISTER FORM		++++++++++++++++++++++++++++++++++++++++++	-->
	<form action="" method="post" class="start_seller">

		<input type="submit" value="Registration" name="start_seller" style="color: white; left: 18%;"> <br/>
		 
		<hr style="border: 1px solid #C5C5C5; width: 50%;"/>
		
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