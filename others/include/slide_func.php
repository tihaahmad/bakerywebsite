<?php
  
  require_once "../Dbh.php";
  
  require_once "../data.contr.php";
  $user = new User($connect);
  $admin = new Admin($connect);


  if($user->is_loggedin()) { 


    
    $userdata = $user->finduserdata($_SESSION['user_session']);

    if(!empty($userdata['email'])) {

      if($userdata['user_role'] == 3) {

        $user_role = $userdata['user_role'];
        $slide = $admin->findslide();
        $slide_path = $slide[0]['img_path'];
        $slide_name1 = $slide[0]['img_name'];
        $slide_name2 = $slide[1]['img_name'];



        $filePath = "img/admin/" ;     //  use for record in db and display to landing
        $directory = "../img/admin/";    //  use for file checking and uplaod to directory
        
        if(!file_exists($directory)) {
          mkdir($directory, 0777, true) ;
        }

    $admindata = $admin->findadminid($userdata['id']);
    $adminName = $admindata['admin_name'];
    
    if(isset($_POST['upload_slide1'])) {

      // UPLOAD FILES
      $fileName = $_FILES['file-1']['name'] ;
      $fileType = $_FILES['file-1']['type'] ;
      $fileSize = $_FILES['file-1']['size'] ;
      $fileTmp = $_FILES['file-1']['tmp_name'] ;


      if($admin->fileChecking($slide_path, $slide_name1, $slide_name2, $slide_name3, $slide_name4, $directory, $fileName, $fileSize, $fileType, $fileTmp) == 1) {
        echo "Sorry, your file cannot bigger than 15MB.";
      } 

      if($admin->fileChecking($slide_path, $slide_name1, $slide_name2, $slide_name3, $slide_name4, $directory, $fileName, $fileSize, $fileType, $fileTmp) == 2) {
        $error = "Sorry, your file only accept jpg, jpeg, png or gif.";
      } 

      if($admin->fileChecking($slide_path, $slide_name1, $slide_name2, $slide_name3, $slide_name4, $directory, $fileName, $fileSize, $fileType, $fileTmp) == 3) {
         echo "Success uploaded." ;
         unlink("../".$slide_path.$slide_name1) ; 
      }

      if($admin->uploadSlide1($user_role, $filePath, $fileName, $adminName)) {
        echo  "success";
      } else {
        echo "fail";
      }


    }   /*  close for SUMMIT BUTTON  */


      
      // if(empty($error)) {
      //   //  UPDATE SUCCESS
      //   if($user->update($user_id, $user_email, $up_username, $up_pnum, $directory, $fileName)) {
      //     $_SESSION['success'] = "Your information was updated successfully." ;
      //     $user->redirect('../others/update.php') ;

      //   } else {
      //     $error = "Sorry, something went wrong with the update.";
      //   }
        
      // }
      
      
      } else {
        $user->redirect('../index.php');
      }

    } else {
      $user->redirect('../index.php');
    }
    
  } else {
    $user->redirect('../index,php');
  }


?>