<?php

	require_once "../Dbh.php";
	
	require_once "../data.contr.php";
	$user = new User($connect);
	$admin = new Admin($connect);
	
	
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
<!DOCTYPE html>
<html>
<head>
	<title>EduKy</title>

	<?php include 'cart.php'; ?>

</head>
<body> 
    <div class="nav">
			<li><a href="seller_start.php" name="seller" class="seller_button"> 
				<?php if(isset($isSeller)) { echo $isSeller; } ?>
				<?php if(isset($isNotseller)) { echo $isNotseller; } ?>
		  </a></li>
			<a href="../index.php"> Home </a>
			<a href='others/user_cart.php'> Cart </a>
        <p> 
        	<?php if(isset($welcome) && !empty($user_imgPath) && !empty($user_imgName)) { 
        		echo $welcome.'<span class="text-whiteblue">'.$userdata['username']. '</span>'; 

        	} else if(isset($welcome) && empty($user_imgPath) && empty($user_imgName)) {
        		echo $welcome.'<span class="text-whiteblue">'.$userdata['username']. '</span>'; 
        	} ?> 

        <?php if(isset($pleaseSignin)) { echo '<span class="text-primary">'.$pleaseSignin; } ?>
				<?php if(isset($register)) { echo $register; } ?>
				<?php if(isset($noLogin)) { echo $noLogin; } ?>
				<?php if(isset($admin_panel)) { echo $admin_panel; } ?>
				<?php if(isset($user_setting)) { echo $user_setting; } ?>
				<?php if(isset($history)) { echo $history; } ?>
        <?php if(isset($logout)) { echo $logout; } ?>
      </div>
</body>

</html>