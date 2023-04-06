<?php

	require_once "Dbh.php";
	
	require_once "data.contr.php";
	$user = new User($connect);
	
	
	if($user->is_loggedin()) {

		$userdata = $user->finduserdata($_SESSION['user_session']);

		if(!empty($userdata['email'])) {

		$welcome = "Hi, ";

		$user_setting = "<a href=others/user_setting.php ><span class=dropdown_icon>&#10148; </span>  User Setting </a>";
		$history = "<a href=others/user_history.php ><span class=dropdown_icon>&#10148; </span>  History </a>";
		$logout = "<a href=index.php?logout=true name=logout class='logout'><span class=dropdown_icon>&#10148; </span>  Logout </a>";

		$user_id = $userdata['id'];
		$user_role = $userdata['user_role'];
		$user_name = $userdata['username'];
		$user_imgPath = $userdata['img_path'];
		$user_imgName = $userdata['img_name'];

		if($user_role == 3) {
			$admin_panel = "<a href=others/admin_panel.php ><span class=dropdown_icon>&#10148; </span>  Admin Panel </a>";
		}

		if($user_role == 2 || $user_role == 3) {
			$isSeller = "Seller";
		} else {
			$isNotseller = "Register as Seller";
		}

		
		if(isset($_GET['logout'])) {
			$user->removeRemember($_SESSION['user_session']);
			if($user->logout()) {
				$user->redirect('index.php');
			} else {
				echo 'Something error with logout class';
			}
		}

		}

	} else {
		
		$register = "<a href=others/user_register.php ><span class=dropdown_icon>&#10148; </span>  Register </a>";
		$noLogin = "<a href=others/user_login.php ><span class=dropdown_icon>&#10148; </span>  Login </a>";
		$pleaseSignin = "Sign In ? ";
		$isNotseller = "Seller Registration";
		
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<?php  include 'homestyle.php'; ?>

</head>
<body> 
	<nav class="navbar">
	
	<div class="logo">Netify</div>
	
	<ul class="nav-links">
	
	<input type="checkbox" id="checkbox_toggle" />
    <label for="checkbox_toggle" class="hamburger">&#9776;</label>

	<div class="menu">
	
			<li><?php if(isset($noLogin)) { echo $noLogin; } ?></li>
			
			<li><?php if(isset($register)) { echo $register; } ?></li>
			
			<li><?php if(isset($welcome) && !empty($user_imgPath) && !empty($user_imgName)) { 
        		echo $welcome.'<span class="text-whiteblue">'.$userdata['username']. '</span>';

        	} else if(isset($welcome) && empty($user_imgPath) && empty($user_imgName)) {
        		echo $welcome.'<span class="text-whiteblue">'.$userdata['username']. '</span>'; 
        	} ?></li>
			
			 <li class="Home">
			
             <a href="index.php"> Home </a>
			
			 <ul class="dropdown">
				 <li><?php if(isset($admin_panel)) { echo $admin_panel; } ?></li>
				 <li><?php if(isset($user_setting)) { echo $user_setting; } ?></li>
				 <li><?php if(isset($history)) { echo $history; } ?></li>
                 <li><?php if(isset($logout)) { echo $logout; } ?></li>
		     </ul>
		     </li>
		
			 <li><a href="others/seller_start.php" name="seller" class="seller_button"> 
		<?php if(isset($isSeller)) { echo $isSeller; } ?>
		<?php if(isset($isNotseller)) { echo $isNotseller; } ?>
		</a></li>
		  
		<li><a href='others/user_cart.php'> Cart </a></li>
		
        </div>
		
        </ul>
		
       </nav>
	   
	</body>
	
</html>