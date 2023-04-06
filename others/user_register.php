<?php 
	require_once "../Dbh.php" ;
	
	require_once "../data.contr.php" ;
	$user = new User($connect) ;

	
  if(isset($_POST['reg_btn'])) {
    
    $username = trim($_POST['reg_username']);
    $email = trim($_POST['reg_email'] );
    $password = trim($_POST['reg_pass']);
    $con_pass = trim($_POST['reg_conPass']);
    
    if($username == "") {
      $error1 = "Please enter your username!";
    } else if ($email == "") {
      $error2 = "Please enter your email!";
    } else if ($password == "") {
      $error3 = "Please enter your password!";
    } else if ($con_pass == "") {
      $error4 = "Please confirm your password!";
    } else if (strlen($password) < 6) {
      $error5 = "Password must be at least 6 characters!";
    } else if ($password != $con_pass) {
      $error6 = "Two passwords are not matched!";
    } else {

    	// $checkExists = $user->checkexistsEmail();  

    	// foreach($checkExists as $key => $value) {
    	// 	$checkEmails = $checkExists[$key]['email'];
    	// 	$checkUsernames = $checkExists[$key]['username'];
    	// }

      if(!filter_var($email,FILTER_VALIDATE_EMAIL)) {
        $error7 = "Please enter a valid email!";
      } 

      // if($username == $checkUsernames) {
      // 	$error[] = "This username has been exists.";
      // } 

      // if($email == $checkEmails) {
      // 	$error[] = "This email has been exists.";
      // } 


    try {
      if($user->user_register($username, $email, $password)) {
        $success = "Register Successfully!";
      } else {
      	$fail = "Sorry, something went wrong with register.";
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

</head>
<body> 

   <?php include 'include/header.php'; ?>
	<header class="home">

		
	<!--	++++++++++++++++++++++++++++++++++++++++++		USER ICON		++++++++++++++++++++++++++++++++++++++++++	-->
			<img src="../img/logo.jpg" alt="seller icon" width="200" height="180" class="userIcon" />
	<table style="margin: 0 auto;"><tr>
		<td style="width:29%"><hr/></td>
		<td class="signUp_title">Create Account</td>
		<td style="width:29%"><hr/></td>
	</tr></table>

	<?php if(isset($success)) {
			echo "<div class='addProductSuccess'> <i class='fas fa-check-circle'></i> <span>". 
			$success. " </span> </div>".
			"<a href='user_login.php' class='sregister_redirect'> Go Login <i class='fa-solid fa-arrow-right'></i></a>	";
		}
	?>

	<?php if(isset($fail)) {
		echo "<div class='usetting_fail'> <i class='fas fa-times-circle'></i> <span>". 
		$fail. ' </span> </div>';
		}
	?>
		
	
	<!--	++++++++++++++++++++++++++++++++++++++++++		REGISTER FORM		++++++++++++++++++++++++++++++++++++++++++	-->
	<form action="" method="post" class="loginform">

		<input type="text" name="reg_username" placeholder="&#xF007;  Username" value="">
		<i style="font-size: 13px;margin: 6%;font-family: Arial, FontAwesome;color: #1976D2;">&#xf0da;  Max length : 8 characters.</i>
		<?php if(isset($error1)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error1 . " </span>"; } ?>

		<input type="email" name="reg_email" placeholder="&#xf0e0;  @gmail.com" value="">
		<?php if(isset($error2)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error2 . " </span>"; } ?>
		<?php if(isset($error7)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error7 . " </span>"; } ?>

		<input type="password" name="reg_pass" placeholder="&#xf023;  Password" value="" style="margin-bottom:0 !important;" > 
		<i style="font-size: 13px;margin: 6%;font-family: Arial, FontAwesome;color: #1976D2;">&#xf0da;  Password must be at least 6 characters.</i>
		<?php if(isset($error3)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error3 . " </span>"; } ?>
		<?php if(isset($error5)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error5 . " </span>"; } ?>
		<?php if(isset($error6)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error6 . " </span>"; } ?>
		
		
		<input type="password" name="reg_conPass" placeholder="&#xf023  Confirm Password" value="">
		<?php if(isset($error4)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error4 . " </span>"; } ?>
    
    
    <br/>
		<input type="submit" value="Register" name="reg_btn" style="color: white; left: 18%;"> <br/>
		 
		<hr style="border: 1px solid #C5C5C5; width: 75%;"/>
		
		<div class="signIn_opt" >
		<p>Already have an account? <a href="user_login.php" > Sign-In</a></p>
		</div>
		
	</form>

</body>
</html>
