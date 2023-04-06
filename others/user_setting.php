<?php
	
	require_once "../Dbh.php";
	
	require_once "../data.contr.php";
	$user = new User($connect);
	$admin = new Admin($connect);
	
	if($user->is_loggedin()) {
		
		$userdata = $user->finduserdata($_SESSION['user_session']);
		
		if(!empty($userdata['email'])) {
			
			$user_id = $userdata['id'];
			$user_name = $userdata['username'];
			$user_fullname = $userdata['full_name'];
			$user_email = $userdata['email'];
			$user_phone = $userdata['phone_num'];
			$user_address = $userdata['address'];
			$user_imgPath = $userdata['img_path'];
			$user_imgName = $userdata['img_name'];

			$directory = '../img/userGallery/';
			$img_path = 'img/userGallery/';

			if(!file_exists($directory)) {
				mkdir($directory);
			}
			


			//		USER UPDATE / USER SETTING
			if(isset($_POST['update_btn'])) {

				if(empty($_POST['full_name'])) {
					$error0 = "Your full name is empty.";
				}
				
				if(empty($_POST['up_username'])) {
					$error1 = "Your username is empty.";
				} 
				
				if(empty($_POST['up_phone_num'])) {
					$error2 = "Your phone number is empty.";
				} 
				
				if(empty($_POST['up_address'])) {
					$error3 = "Your address is empty.";
				}
				
				$up_name = $_POST['up_username'];
				$full_name = $_POST['full_name'];
				$up_phone = $_POST['up_phone_num'];
				$up_address = $_POST['up_address'];

				
				if(!empty($_POST['up_username']) && !empty($_POST['full_name']) && !empty($_POST['up_phone_num']) && !empty($_POST['up_address'])) {

					if($user->user_setting($user_id, $user_email, $up_name, $full_name, $up_phone, $up_address)) {
						$_SESSION['updated_sucess'] = "Your information was updated successfully..." ;
						$user->redirect('../others/user_setting.php');
							
					} else {
							$error4 = "Sorry, something went wrong with the update.";
					}

				}
			} // CLOSE FOR UPDATE BUTTON



			//	FILE UPLOADING BUTTON
			if(isset($_POST['upload_profile'])) {

				$fileName = $_FILES['file']['name'] ;
				$fileType = $_FILES['file']['type'] ;
				$fileSize = $_FILES['file']['size'] ;
				$fileTmp = $_FILES['file']['tmp_name'] ;

				if(!empty($fileName) && !empty($fileType) && !empty($fileSize) && !empty($fileTmp)) {
					if($admin->fileChecking($directory, $fileName, $fileSize, $fileType, $fileTmp) == 1) {
					  $error[] = "Sorry, your file cannot bigger than 15MB.";
					} else if($admin->fileChecking($directory, $fileName, $fileSize, $fileType, $fileTmp) == 2) {
					  $error[] = "Sorry, your file only accept jpg, jpeg or png.";
					} else {

						if($user->userProfileimg($user_id, $user_email, $img_path, $fileName)) {
							unlink("../".$user_imgPath.$user_imgName); 
							$_SESSION['profilepic_upload_true'] = "Uploaded successfully." ;
							$user->redirect('../others/user_setting.php#account') ;
						} else {
							$error[] = "Sorry, something went wrong with uploading.";
						}

						}
				} else {
						$error[] = "Sorry, please choose an image.";
					}
			}





			//  CHANGE PASSWORD FUNCTION
			if(isset($_POST['change_password'])) {
				$cur_pass = trim($_POST['cur_pass']);
				$new_pass = trim($_POST['new_pass']);
				$con_pass = trim($_POST['con_pass']);

				if(empty($cur_pass)) {
					$passErr1 = "Please enter your current password.";
				} else {

					if($user->checkCurrpass($user_id, $cur_pass)) {
						if(empty($new_pass)) {
						  $passErr2 = "Please enter your new password.";
						} else if(empty($con_pass)) {
						  	$passErr3 = "Please confirm your new password.";
						  } else if(strlen($new_pass) < 6) {
						  	$passErr4 = "Your password must at least 6 characters.";
						  } else if($con_pass != $new_pass) {
						  	$passErr5 = "Your new passwords are not matched.";
						  } else {
						  	if($user->changePass($user_id, $user_name, $new_pass)) {
						  		$_SESSION['user_change_password'] = "Your password was changed successfully." ;
						  		$user->redirect('../others/user_setting.php#password') ;
						  	} else {
						  		$passFail = "Sorry, something went wrong with changes.";
						  	}
						  }
					} else {
						$passErr6 = "Sorry, your current password is wrong.";
					}
				}
			}   // CLOSE PASSWORD SUBMIT BUTTON
			
?>

<!DOCTYPE html>
<html>
<head>
	<title> Android </title>

	<?php include 'include/homestyle.php'; ?>

</head>
<body> 



	<!--	++++++++++++++++++++++++++++++++++++++++++		NAVIGATION		++++++++++++++++++++++++++++++++++++++++++	-->
	

	<div id="account"></div>

	<div class="usetting_selector" id="usetting_selector">
		<a href="#account" class="usetting_selector selector usetting_active"> <i class="fa-solid fa-id-card"></i> Account Setting</a>
		<a href="#password" class="usetting_selector selector"> <i class="fa-solid fa-key"></i> Password Setting</a>
	</div>

	

	<!--	===========================	ACCOUNT SETTING / USER SETTING	-->
	<?php if(isset($_SESSION['updated_sucess'])) {
		 echo '<div class="usetting_success"> <i class="fas fa-check-circle"></i> <span>'. 
		 $_SESSION["updated_sucess"]. ' </span> 	</div>'.
		 "<div class='usetting_sucess_redirect'>
		 <a href='../index.php' class='usetting_success_back'> <i class='fas fa-arrow-left'></i> Home</a>
		 <a href='user_cart.php' class='redirect_back_cart'> Cart <i class='fa-solid fa-arrow-up-right-from-square'></i></a>
		 </div>";
		 unset($_SESSION['updated_sucess']);
		}
	?>

	<?php if(isset($error4)) {
		echo "<div class='usetting_fail'> <i class='fas fa-times-circle'></i> <span>". 
		$error4. ' </span> </div>';
		}
	?>

	<div class="setting-container mt-0">
	<span class="setting-title"> Account Setting </span>

	<form action="" method="post" class="usetting_con" enctype="multipart/form-data">
		<div class="setting-form">

		<div class="setting-boxes">
			<label for="full_name">FULL NAME </label><br/>
			<input type="text" name="full_name" placeholder="&#xf2bd;  Enter your full name" value="<?php echo $user_fullname; ?>">
			<i style="font-size: 13px;margin: 10% 6% 0 1%; position: relative; top: 5px;font-family: Arial, FontAwesome;color: #1976D2;">&#xf0da;  Receiver Name. &nbsp;&nbsp;  Exp: Keng chu hua</i>
			<?php if(isset($error0)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error0 . " </span>"; } ?>
		</div>

		<div class="setting-boxes">
			<label for="up_username">Username </label><br/>
			<input type="text" name="up_username" placeholder="&#xf2bd;  Enter your username" value="<?php echo $user_name; ?>">
			<?php if(isset($error1)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error1 . " </span>"; } ?>
		</div>


		<div class="setting-boxes">
			<label for="up_phone_num">Phone number </label><br/>
			<input type="number" name="up_phone_num" placeholder="&#xf095;  Exp.0123456789" value="<?php echo $user_phone; ?>">
			<?php if(isset($error2)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error2 . " </span>"; } ?>
		</div>	

		
		<div class="setting-boxes">
			<label for="up_address">Address </label><br/>
			<input type="text" name="up_address" placeholder=" &#xf041; 5,jalan test 1/2..." value="<?php echo $user_address; ?>">
			<?php if(isset($error3)) { echo "<span class='usetting_false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error3 . " </span>"; } ?>
			<?php if(isset($error4)) { echo "<span class='usetting_false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error4 . " </span>"; } ?>
		</div>	

		<input type="submit" value="Save" name="update_btn">
		</div>

		<div class="user_profile"> 
			<div class="user_profile_con" style="margin-top: 25%;">
				<?php if(isset($_SESSION['profilepic_upload_true'])) {
					 echo "<span class='propic_false' style='font-size: 1.1rem; color: #5DCF7D !important;'> <i class='fas fa-check-circle' style='margin-right: 2%;'></i>". 
					 $_SESSION["profilepic_upload_true"]. " </span>";
					 unset($_SESSION['profilepic_upload_true']);
					}
				?>
			  <div class="user_profile_img">

			    <div class="filePreview"> 
			    	<img id="filePreview" name="up_image" src=" 
			    	<?php
			    		if(!empty($user_imgPath) && !empty($user_imgName)) {
			    			echo "../".$user_imgPath. $user_imgName ;
			    		} else {
			    			echo "../img/defaultImg/product_default.jpg";
			    		}
			    	?>">
			    </div>
			      <label for="file">Add an image</label>
			      <input type="file" id="file" name="file" accept="media/*" onchange="showPreview(event);">
			      <small> File extension: .jpg, .png </small>
			  </div>

			  <?php  if(isset($error)) {
			    foreach($error as $err) {
			      echo "<span class='propic_false'> <i class='fa-solid fa-circle-exclamation'></i>  " .$err. "</span>";
			    }
			  }  ?>

			  <input type="submit" class="usetting_upload" value="Upload" name="upload_profile">
			</div>
		</div>

	</form>
	<a href="../index.php"> BACK </a>
	</div>



	<div id="password"></div>

	<!--	===========================	PASSWORD	-->
	<?php if(isset($_SESSION['user_change_password'])) {
		 echo '<div class="usetting_success"> <i class="fas fa-check-circle"></i> <span>'. 
		 $_SESSION["user_change_password"]. ' </span> '.
		 "<a href='../index.php' class='usetting_success_back'> <i class='fas fa-arrow-left'></i> Back</a>	</div>";
		 unset($_SESSION['user_change_password']);
		}
	?>

	<?php if(isset($passFail)) {
		echo "<div class='usetting_fail'> <i class='fas fa-times-circle'></i> <span>". 
		$passFail. ' </span> </div>';
		}
	?>

	<div class="setting-container" style="margin-top: 13% !important;">
	<span class="setting-title"> Password Setting </span>

	<form action="" method="post" class="usetting_con" enctype="multipart/form-data">
		<div class="setting-form">

		<div class="setting-boxes border-none">
			<label for="cur_pass">Current Password </label><br/>
			<input type="password" name="cur_pass" placeholder="  Enter your Current password" value="">
			<?php if(isset($passErr1)) { echo "<span class='usetting_false'><i class='fas fa-exclamation-circle'></i>" . $passErr1 . " </span>"; } ?>
			<?php if(isset($passErr6)) { echo "<span class='usetting_false'><i class='fas fa-exclamation-circle'></i>" . $passErr6 . " </span>"; } ?>
		</div>


		<div class="setting-boxes border-none">
			<label for="new_pass">New Password </label><br/>
			<input type="password" name="new_pass" placeholder="  Enter your New password" value="">
			<?php if(isset($passErr2)) { echo "<span class='usetting_false'><i class='fas fa-exclamation-circle'></i>" . $passErr2 . " </span>"; } ?>
			<?php if(isset($passErr4)) { echo "<span class='usetting_false'><i class='fas fa-exclamation-circle'></i>" . $passErr4 . " </span>"; } ?>
			<?php if(isset($passErr5)) { echo "<span class='usetting_false'><i class='fas fa-exclamation-circle'></i>" . $passErr5 . " </span>"; } ?>
		</div>	

		
		<div class="setting-boxes border-none ">
			<label for="con_pass">Confirm Passowrd </label><br/>
			<input type="password" name="con_pass" placeholder="  Confirm your New password" value="">
			<?php if(isset($passErr3)) { echo "<span class='usetting_false'><i class='fas fa-exclamation-circle'></i>" . $passErr3 . " </span>"; } ?>
			<?php if(isset($passErr5)) { echo "<span class='usetting_false'><i class='fas fa-exclamation-circle'></i>" . $passErr5 . " </span>"; } ?>
		</div>	

		<input type="submit" value="Change password" name="change_password" class="change_pass">
		</div>

	</form>
	<a href="../index.php" class="mb-4"> BACK </a>
	<a href="#top" class="back_top"> Back to top</a>
	</div>

</body>
</html>

<?php

		} else {
			$user->redirect("../login.php");
		} // if user_session didnt have email

	} else {
		$user->redirect("../login.php");
	}	// if user not login

?>

<!-- *******************		PRELOADER		*******************	 -->
<script>
        $(window).on("load",function(){
          $(".container").fadeOut("slow");
        });
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

<script type="text/javascript">
  // Get the container element
  var btnContainer = document.getElementById("usetting_selector");

  // Get all buttons with class="btn" inside the container
  var btns = btnContainer.getElementsByClassName("selector");

  // Loop through the buttons and add the active class to the current/clicked button
  for (var i = 0; i < btns.length; i++) {
    btns[i].addEventListener("click", function() {
      var current = document.getElementsByClassName("usetting_active");
      current[0].className = current[0].className.replace(" usetting_active", "");
      this.className += " usetting_active";
    });
  }
</script>