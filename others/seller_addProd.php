<?php
	
	require_once "../Dbh.php";
	
	require_once "../data.contr.php";
	$user = new User($connect);
	$admin = new Admin($connect);
	
	if($user->is_loggedin()) {
		
		$userdata = $user->finduserdata($_SESSION['user_session']);
		
		if(!empty($userdata['email'])) {

			$user_id = $userdata['id'];
			$user_email = $userdata['email'];
			$user_role = $userdata['user_role'];
			$user_name = $userdata['username'];
			$user_imgPath = $userdata['img_path'];
			$user_imgName = $userdata['img_name'];	

			if($user_role == 2 || $user_role == 3) {

				$sellerdata = $user->findsellerid($user_id);

				if(!empty ($sellerdata['user_id'])) {

					$sellerId = $sellerdata['id'];

					$pdetail_dir = '../img/sellerGallery/';
					$pcover_display = 'img/sellerGallery/';

					$category = $admin->findCat();

					if(!file_exists($pdetail_dir)) {
						mkdir($pdetail_dir);
					}

					

					if(isset($_POST['add_product'])) {

						$imgName = $_FILES['file-cover']['name'] ;
						$imgType = $_FILES['file-cover']['type'] ;
						$imgSize = $_FILES['file-cover']['size'] ;
						$imgTmp = $_FILES['file-cover']['tmp_name'] ;

						if($_POST['productName'] == "") {
							$perror1 = "Sorry, your product name is empty.";
						} else if($_POST['productPrice'] == "") {
							$perror3 = "Sorry, your product price is empty.";
						} else if($_POST['productQuantity'] == "") {
							$perror4 = "Sorry, your product quantity is empty.";
						} else {

							if(!empty($imgName) && !empty($imgSize) && !empty($imgType) && !empty($imgTmp) ) {
								if($imgSize < 104857600) {
									if($imgType == 'image/jpg' || $imgType == 'image/jpeg' || $imgType == 'image/png' || $imgType == 'image/gif') {
										move_uploaded_file($imgTmp, "../img/sellerGallery/" .$imgName);
									} else {
										$coverErr[] = "Sorry, your file extension is invalid.";
									}
								} else {
									$coverErr[] = "Sorry, your file cannot bigger than 30MB.";
								}
							}	else {
								$coverErr[] = "Sorry, your cover image is empty.";
							}	

							$productName = $_POST['productName'];
							$productCat = $_POST['category'];
							$productPrice = $_POST['productPrice'];
							$productQuantity = $_POST['productQuantity'];
							

							if($user->addProduct($sellerId, $pcover_display, $imgName, $productName, $productPrice, $productQuantity, $productCat) ) {

								$selectPid = $user->selectproductId($sellerId);
								$productId = $selectPid['id'];

								$productNum = count($_FILES['files']['name']);

								for ($i=0; $i < $productNum ; $i++) {
									$pName = $_FILES['files']['name'][$i];
									$pSize = $_FILES['files']['size'][$i];
									$pType = $_FILES['files']['type'][$i];
									$pTmp = $_FILES['files']['tmp_name'][$i];

								if(!empty($pName) && !empty($pSize) && !empty($pType) && !empty($pTmp) ) {
									if($pSize < 104857600) {
										if($pType == 'image/jpg' || $pType == 'image/jpeg' || $pType == 'image/png' || $pType == 'image/jfif') {
											move_uploaded_file($pTmp, "../img/sellerGallery/" .$pName);
										} else {
											$error[] = "Sorry, your file extension is invalid.";
										}
									} else {
										$error[] = "Sorry, your file cannot bigger than 30MB.";
									}
								}	else {
									$error[] = "Sorry, you may insert several images.";
								}	

									if(empty($error)) {
										if($user->insertpDetail($productId, $pdetail_dir, $pName, $sellerId)) {
											$_SESSION['add_product_success'] =  "Add product Successfully !";

										} else {
											$addProductfail = "Sorry, something went wrong.";
										}
									}		
								}			
							}			
						}
					}				



?>

<!DOCTYPE html>
<html>
<head>
	<title>Seller</title>
	
	<link type="text/css" rel="stylesheet" href="../css/cart-style.css" />
	<link rel="stylesheet" type="text/css" href="../css/slideshow.css" />
	<link rel="stylesheet" type="text/css" href="../css/navbar.css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
	<link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css" />

</head>
<body> 

		<?php include 'include/sellerNav.php'; ?>


		<div class="sellerNav" id="SellerNav">
	  <a href="#about" class="seller_panel_img" class="btn" >  <img src="

	  <?php
	  	if(!empty($user_imgPath) && !empty($user_imgName)) {
	  		echo "../".$user_imgPath. $user_imgName ;
	  	} else {
	  		echo "../img/defaultImg/u_default.png";
	  	}
	  ?>
	  	" alt="Seller_Profile_Image" width="100%"> </a>
	  <p> <?php echo $user_name; ?> </p>

	  <a href="seller_setting.php" class="btn " > <i class="fas fa-user"></i>  Account</a>
	  <a href="seller_page.php" class="btn sellerNav_active" >  <i class="fas fa-box"></i>  Products</a>
	  <a href="#clients" class="btn" > <i class="fas fa-clipboard-list"></i>    Orders</a>

	  <a href="seller_page.php" class="admin_back"> <i class="fas fa-chevron-circle-left"></i>Back</a>

	</div>


	<?php if(isset($pImgErr)){
		foreach($pImgErr as $err) {
		echo $err;
	} 
	} ?>



<div class="seller_product_container1">
  <h2 class="seller_panel_title">  Add a product 		<i class="far fa-plus-square"></i>	</h2>


  <form action="" method="post" class="seller_add" enctype="multipart/form-data">

  	<?php if(isset($_SESSION['add_product_success'])) {
  			echo "<div class='addProductSuccess'> <i class='fas fa-check-circle'></i> <span>". 
  			$_SESSION['add_product_success']. " </span> </div>".
  			"<a href='seller_page.php' class='addProductBack'> <i class='fas fa-arrow-left'></i> Back</a>	";
  		 	unset($_SESSION['add_product_success']);
  		}
  	?>

  	<?php if(isset($addProductfail)) {
  		echo "<div class='addProductFail'> <i class='fas fa-times-circle'></i> <span>". 
  		$addProductfail. ' </span> </div>';
  		}
  	?>	

  	<p> <i class="fas fa-exclamation"></i> fill in your product information.</p>

  	<label for="prod_cover"> Product Cover</label><br>
  	<div class="seller_cover_container">

  	  <div class="seller_cover">

  	    <?php  if(isset($catError)) {
  	      foreach($catError as $err) {
  	        echo "<span class='cat_false'> &#xf0da;  " .$err. "</span>";
  	      }
  	    }  ?>

  	    <div class="previewCover">   <img id="file-cover-previewCover" src="../img/admin/default.png">    </div>
  	      <label for="file-cover" style="background-color: #4CAF50; margin-left: 20% !important;">Add a Cover</label>
  	      <input type="file" id="file-cover" name="file-cover" accept="media/*" onchange="showPreviewCover(event);">
  	  </div>
  	</div>

  	<label for="seller_pname"> Product Name </label>
  	<input type="text" name="productName" placeholder="Enter a product name" value="">
  	<?php if(isset($perror1)) { echo "<span class='addProductFalse'> &#xf0da;  " .$perror1. "</span>"; } ?>

  	<label for="seller_pcategory"> Product Category </label>
  	<select name="category" id="category" class="seller_addcat">
  		<?php
  			foreach($category as $value) {
  				echo
  				' <option value="'.$value['category_name'].'">'.$value['category_name'].'</option>';
  			}
  		?>
  	</select>

  	<label for="seller_pPrice"> Product Price </label>
  	<input type="text" name="productPrice" placeholder="Exp. 9.99" value="">
  	<small> Exp: 290.00</small>
  	<?php if(isset($perror3)) { echo "<span class='addProductFalse'> &#xf0da;  " .$perror3. "</span>"; } ?>

  	<label for="seller_pinStock"> Product Quantity </label>
  	<input type="number" name="productQuantity" placeholder="Enter a product quantity" value="" >
  	<small> You can only insert an interger. Exp. 10</small>
  	<?php if(isset($perror4)) { echo "<span class='addProductFalse'> &#xf0da;  " .$perror4. "</span>"; } ?>

  	<label for="seller_pdetail"> Insert more images about product </label>
  	<?php
  	  if(isset($error)) {
  	    foreach($error as $err) {
  	      echo "<span class='addProductFalse'> &#xf0da; " . $err . " </span>" ;
  	    }
  	  }
  	?>

  	<small id="here" style="font-size: 0.95rem;"> <i class="fas fa-exclamation" style="color: var(--error);"></i> File extension: .jpg, .png</small>
  	<input type="file" id="files" name="files[]" accept="image/*" style="margin-bottom: 6px;" multiple />


  	<input type="submit" id="#" style="background-color: #4CAF50; margin-left: 20% !important;" name="add_product" value="Add product" />

  </form>

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

<script type="text/javascript">
  
    function showPreviewCover(event){
  if(event.target.files.length > 0){
    var src = URL.createObjectURL(event.target.files[0]);
    var preview = document.getElementById("file-cover-previewCover");
    preview.src = src;
    preview.style.display = "block";
  }
}

</script>
<script>
/* ------------------------------------------------- UPLOAD IMAGE ------------------------------------------------- */
$(document).ready(function() {
  if (window.File && window.FileList && window.FileReader) {
    $("#files").on("change", function(event) {
      var files = event.target.files,
        filesLength = files.length;
      for (var i = 0; i < filesLength; i++) {
        var f = files[i];
        var fileReader = new FileReader();
        fileReader.onload = (function(event) {
          var file = event.target;
          $("<span class=seller_pdetail>" +
            "<img class=seller_imageThumb src=" + event.target.result + " title=" + file.name + "/>" + // 
            "<br/><span class=seller_premove>Remove image</span>" +
            "</span>").insertAfter("#files");
          $(".seller_premove").click(function(){
            $(this).parent(".seller_pdetail").remove();
          });

        });
        fileReader.readAsDataURL(f);
      }
    });

		
  } else {
    alert("Your browser doesn't support to File API")
  }
});
</script>