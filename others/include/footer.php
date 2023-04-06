<?php

  require_once "../Dbh.php";
  
  require_once "../data.contr.php";
  $user = new User($connect);
  $admin = new Admin($connect);

  $category = $admin->findCat();  

?>

<!DOCTYPE html>
<!-- Created By CodingLab - www.codinglabweb.com -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="../css/footer.css">


     <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
   </head>
<body>
 <footer>
   <div class="content">
     <div class="left box">
       <div class="upper">
         <div class="topic primary_blue">Netify</div>
         <p>Experience electronic bliss by walking through our doors.</p>
       </div>
       <div class="lower">
         <div class="topic">Our Category</div>
<!--          <div class="terms">
           <a href="#">Terms & Conditions</a>
         </div>
         <div class="policy">
           <a href="#">Privacy Policy</a>
         </div> -->
         <?php
          foreach ($category as $value) {
            echo
            '<div class="'.$value['category_name'].'">
            <a href="user_category_page.php?category='.$value['id'].'">'.$value['category_name'].'</a>
            </div>';
          }
         ?>
       </div>
     </div>
     <div class="middle box">
       <div class="topic">Menu</div>
       <div><a href="../index.php">Home</a></div>
       <div><a href="user_setting.php">User Setting</a></div>
       <div><a href="user_wishlist.php">Whishlist</a></div>
       <div><a href="user_cart.php">Cart</a></div>
       <div><a href="seller_page.php">Seller</a></div>
     </div>
     <div class="right box">
       <div class="topic">Follow us</div>
       <form action="#">
<!--          <input type="text" placeholder="Enter email address">
         <input type="submit" name="" value="Send"> -->
         <div class="media-icons">
           <a href="https://www.facebook.com" class="facebook"><i class="fab fa-facebook-f"></i></a>
           <a href="https://www.instagram.com" class="instagram"><i class="fab fa-instagram"></i></a>
           <a href="https://www.twitter.com" class="twitter"><i class="fab fa-twitter"></i></a>
           <a href="https://www.youtube.com" class="youtube"><i class="fab fa-youtube"></i></a>
         </div>
       </form>
     </div>
   </div>
   <div class="bottom">
     <p>Copyright © 2022 <a href="#">Netify</a> All rights reserved</p>
   </div>
 </footer>

</body>
</html>
