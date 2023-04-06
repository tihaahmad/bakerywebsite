<?php
  

require_once "../Dbh.php";

require_once "../data.contr.php";
$user = new User($connect);
$admin = new Admin($connect);


if($user->is_loggedin()) { 


  
  $userdata = $user->finduserdata($_SESSION['user_session']);

  if(!empty($userdata['email'])) {

    if($userdata['user_role'] == 3) {

      /*  ADMIN VARIABLES  */
      $admindata = $admin->findadminid($userdata['id']);
      $adminId = $admindata['id'];
      $adminName = $admindata['admin_name'];
      $category = $admin->findCat();  


      /*  SLIDE VARIABLES  */
      $user_role = $userdata['user_role'];
      $slide = $admin->findslide();
      $slide_path = $slide[0]['img_path'];
      $slide_name1 = $slide[0]['img_name'];




      $filePath = "img/admin/" ;     
      $directory = "../img/admin/";    
      
      if(!file_exists($directory)) {
        mkdir($directory, 0777, true) ;
      }
 
  $catPath = "img/admin/category/" ;    
  $catDir = "../img/admin/category/";   

  if(!file_exists($catDir)) {
    mkdir($catDir, 0777, true) ;
  }
  
  if(isset($_POST['upload_cat'])) {

    $catFile = $_FILES['file-cat']['name'] ;
    $catSize = $_FILES['file-cat']['size'] ;
    $catType = $_FILES['file-cat']['type'] ;
    $catTmp = $_FILES['file-cat']['tmp_name'] ;

    $catName = ($_POST['cat_name']);

    if(empty($catName)) {
      $catError[] = "Please enter a Name.";
    }

    if(empty($catFile) && empty($catSize) && empty($catType) && empty($catTmp)) {
      $catError[] = "Please choose an image!";
    } else {

    if($admin->fileChecking($catDir, $catFile, $catSize, $catType, $catTmp) == 1) {
      $catError[] = "Sorry, your file cannot bigger than 15MB.";
    } 

    if($admin->fileChecking($catDir, $catFile, $catSize, $catType, $catTmp) == 2) {
      $catError[] = "Sorry, your file only accept jpg, jpeg, png or gif.";
    } 

    if($admin->fileChecking($catDir, $catFile, $catSize, $catType, $catTmp) == 3) {
       // unlink("../".$slide_path.$slide_name1) ; 
    }

    if($admin->createCat($user_role, $catName, $catPath, $catFile, $adminId)) {
      $_SESSION['create_cat_success'] = "Created successfully." ;
      $user->redirect('../others/admin_panel.php#category') ;
    } else {
      $catError[] = "Sorry, something went wrong with the upload.";
    }

    }
  }  


  if(isset($_GET['catid'])) {
    $cat_id = $_GET['catid'];
    if($admin->deleteCategory($cat_id)) {
      $_SESSION['admin_delete_category'] = "Delete successfully!" ;
      $user->redirect('admin_panel.php#category');
    } else {
      $deleteCat_fail = "Sorry, something went wrong with delete.";
    }
  }

?>

<!DOCTYPE html>
<html>
<head>
  <title>Netify</title>

  <?php include 'include/homestyle.php'; ?>

</head>
<body> 
  
  <?php include 'include/adminNav.php'; ?>
  
  <div class="admin_container1" id="category">
    <div class="admin_display_cat">
      <h3> Category list: </h3>
      <?php if(isset($_SESSION['admin_delete_category'])) { echo "<span class='usetting_false' style='width: 100%; font-size: 1.1rem; color: #5DCF7D !important;'> <i class='fas fa-check-circle'></i> " . $_SESSION['admin_delete_category'] . " </span>";
        unset($_SESSION['admin_delete_category']); } ?>

      <?php if(isset($deleteCat_fail)) { echo "<span class='usetting_false' style='width: 100%'> <i class='fa-solid fa-circle-exclamation'></i> " . $deleteCat_fail . " </span>"; } ?>
      <?php
        foreach ($category as $cat) {
          echo
          '<form action="" method="post">'.
          '<div class="admin_cat_con">
            <div class="admin_cat">'.
              '<img src="../'.$cat['category_path'].$cat['category_file'].'" alt="'.$cat['category_name'].'">
              <p> '.$cat['category_name'].' </p>'.
            '</div>'.

           '<a href="admin_panel.php?catid='.$cat['id'].'" class="admin_del_cat"> Delete </a>'.

          '</div>'.
          '</form>';
        }
      ?>
      
    </div>
    <h2> Create a Category </h2>
    <form action="" method="post" enctype="multipart/form-data">
      <div class="admin_slide_container">

        <div class="admin_create_cat">

          <?php  if(isset($catError)) {
            foreach($catError as $err) {
              echo "<span class='cat_false'> &#xf0da;  " .$err. "</span>";
            }
          }  ?>

          <?php if(isset($_SESSION['create_cat_success'])) {
             echo '<span class="cat_true"> &#xf0da; ' .$_SESSION['create_cat_success']. ' </span>';
             unset($_SESSION['create_cat_success']);
            } ?>

          <div class="previewCat">   <img id="file-cat-previewCat" src="../img/admin/category/default.png">    </div>
          <input type="text" name="cat_name" placeholder="Enter Category Name" value="" />
          <div class="admin_inline ">
            <label for="file-cat" style="background-color: #4CAF50;" >Choose Image</label> <input type="submit"  name="upload_cat" value="Create"></div>
            <input type="file" id="file-cat" name="file-cat" accept="media/*" onchange="showPreviewCat(event);">
        </div>
      </div>
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
  $user->redirect('../index,php');
}
?>



