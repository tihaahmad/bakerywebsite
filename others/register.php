<?php 
  require_once "../config.php" ;
  
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
      //  $checkEmails = $checkExists[$key]['email'];
      //  $checkUsernames = $checkExists[$key]['username'];
      // }

      if(!filter_var($email,FILTER_VALIDATE_EMAIL)) {
        $error7 = "Please enter a valid email!";
      } 

      // if($username == $checkUsernames) {
      //  $error[] = "This username has been exists.";
      // } 

      // if($email == $checkEmails) {
      //  $error[] = "This email has been exists.";
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
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register Account</title>
  <link rel="stylesheet" href="../regrister/style.css">
</head>
<body>
    <div class="form_wrapper">
  <div class="form_container">

      <?php if(isset($success)) {
      echo "<div class='addProductSuccess'> <i class='fas fa-check-circle'></i> <span>". 
      $success. " </span> </div>".
      "<a href='user_login.php' class='sregister_redirect'> Go Login <i class='fa-solid fa-arrow-right'></i></a>  ";
    }
  ?>

  <?php if(isset($fail)) {
    echo "<div class='usetting_fail'> <i class='fas fa-times-circle'></i> <span>". 
    $fail. ' </span> </div>';
    }
  ?>

    <div class="title_container">
      <h2>Responsive Registration Form</h2>
    </div>
    <div class="row clearfix">
      <div class="">
        <form action="" method="post">

          <div class="input_field"> <span><i aria-hidden="true" class="fa fa-envelope"></i></span>
            <input type="text" name="reg_username" placeholder="Username" value="" required />
          </div>

          <?php if(isset($error1)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error1 . " </span>"; } ?>

          <div class="input_field"> <span><i aria-hidden="true" class="fa fa-envelope"></i></span>
            <input type="email" name="reg_email" placeholder="Email" value="" required />

            <?php if(isset($error2)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error2 . " </span>"; } ?>
    <?php if(isset($error7)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error7 . " </span>"; } ?>

          </div>
          <div class="input_field"> <span><i aria-hidden="true" class="fa fa-lock"></i></span>
            <input type="password" name="reg_pass" placeholder="Password" value="" required />
          </div>

          <?php if(isset($error3)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error3 . " </span>"; } ?>
    <?php if(isset($error5)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error5 . " </span>"; } ?>
    <?php if(isset($error6)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error6 . " </span>"; } ?>

          <div class="input_field"> <span><i aria-hidden="true" class="fa fa-lock"></i></span>
            <input type="password" name="reg_conPass" placeholder="Re-type Password" value="" required />
          </div>

          <?php if(isset($error4)) { echo "<span class='false'> <i class='fa-solid fa-circle-exclamation'></i> " . $error4 . " </span>"; } ?>

          
          <input type="submit" value="Register" name="reg_btn" class="button" type="submit" value="Register" />

        <div class="signIn_opt" >
          <p>Already have an account? <a href="login.php" > Sign-In</a></p>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- <p class="credit">Developed by <a href="http://www.designtheway.com" target="_blank">Design the way</a></p> -->
</body>
</html>
