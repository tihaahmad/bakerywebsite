<?php 
	require_once "../Dbh.php" ;
	
	require_once "../data.contr.php" ;
	$user = new User($connect) ;

	function generateRandomString($length = 128) {
		return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}

	//Remember me -> redirect to index
	if(!empty($_COOKIE['identifier']) && !empty($_COOKIE['token'])) {
		if($user->checkRemember($_COOKIE['identifier'], $_COOKIE['token'])) {
			$getData = $user->checkRemember($_COOKIE['identifier'], $_COOKIE['token']);
			$_SESSION['user_session'] = $getData['id'];
			$user->redirect('../index.php');
		}
	}
  
  if(isset($_POST['log_btn'])) {
    
    if(empty($_POST['log_email'])) {
      $error1 = "Please enter your email!";
      
    } 
    
    if(empty($_POST['log_pass'])) {
      $error2 = "Please enter your password!";
      
    } 

    $remember = (!empty($_POST['remember'])) ? $_POST['remember'] : '';

    
    if(!empty($_POST['log_email']) && !empty($_POST['log_pass']) ) {
      $email = $_POST['log_email'];
      $password = $_POST['log_pass'];

        if($remember === 'on') {
        	$rememberIdentifier = generateRandomString();
        	$rememberToken = generateRandomString();
        

      if($user->user_login($email, $password)) {
        	if(empty($_COOKIE['identifier']) && empty($_COOKIE['token'])) {
      	  	$user->updateRemember($rememberIdentifier, $rememberToken, $_SESSION['user_session']);
	        	setcookie('identifier', $rememberIdentifier, time() + (86400 * 30), "/");
	        	setcookie('token', $rememberToken, time() + (86400 * 30), "/");
	        } else {
	        	$user->updateRemember($rememberIdentifier, $rememberToken, $_SESSION['user_session']);
	        	setcookie('identifier', $rememberIdentifier, time() + (86400 * 30), "/");
	        	setcookie('token', $rememberToken, time() + (86400 * 30), "/");
	        }
       		
       		$user->redirect('../index.php');
        }

      } else {
      	if($user->user_login($email, $password)) {
      		$user->redirect('../index.php');
      	}
      }
      
    } else {
        $error3 = "Incorrect email or password...";
      } // close for function
    
  } // close for submit button
?>

<!DOCTYPE html>
<html>
<head>
	<title>Sign In</title>

	<?php include 'include/homestyle.php'; ?>

</head>
<body> 

   <?php include 'include/header.php'; ?>
	<header class="home">
	<!--	++++++++++++++++++++++++++++++++++++++++++		USER ICON		++++++++++++++++++++++++++++++++++++++++++	-->
			<img src="../img/logo.jpg" alt="seller icon" width="200" height="180" class="userIcon" />
	<table style="margin: 0 auto;"><tr>
		<td style="width:39%"><hr/></td>
		<td class="signUp_title">Sign In</td>
		<td style="width:39%"><hr/></td>
	</tr></table>
		

	<!--	++++++++++++++++++++++++++++++++++++++++++		LOGIN FORM		++++++++++++++++++++++++++++++++++++++++++	-->
	<form action="" method="post" class="loginform">

		<input type="email" name="log_email" placeholder="&#xf0e0  @gmail.com" value=""> 
      <?php if(isset($error1)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error1 . " </span>"; } ?>
      
		<input type="password" name="log_pass" placeholder="&#xf023  Password" value="" style="margin-bottom:2%;" > 
      <?php if(isset($error2)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error2 . " </span>"; } ?>
      <?php if(isset($error3)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error3 . " </span>"; } ?>

		<input type="submit" value="Login" name="log_btn" style="color: white; left: 18%;"> <br/>
		 
		<hr style="border: 1px solid #C5C5C5; width: 75%;"/>
		
		<div class="signIn_opt" >
		<p>New to Netify? <a href="user_register.php" > Create account</a></p>
		</div>
		
	</form>

</body>

</html>
