<?php 

	require_once "../Dbh.php";
	
	require_once "../data.contr.php";
	$user = new User($connect);


	if($user->is_loggedin()) {
		
		$userdata = $user->finduserdata($_SESSION['user_session']);
		
		if(!empty($userdata['email'])) {

        $user_id = $userdata['id'];
        $user_name = $userdata['username'];
        $user_email = $userdata['email'];
        $user_address = $userdata['address'];

        $cart_data = $user->getCartdata($user_id);

        if(!empty($cart_data)) {


      if(isset($_POST['payment'])) {
        // $user->redirect('thankyou.php');

        if(empty($_POST['cardname'])) {
          $cardErr[] = 'Sorry, please enter your card name.';
        } else if(empty($_POST['cardnumber'])) {
          $cardErr[] = 'Sorry, please enter your card number.';
        } else if(empty($_POST['expiration'])) {
          $cardErr[] = 'Sorry, please enter your card expiration.';
        } else if(empty($_POST['securitycode'])) {
          $cardErr[] = 'Sorry, please enter your security code.';
        } else {

        $sum = $user->sumSubtotal($user_id);
        $subtotal = $sum['subtotal'];
        $total = $subtotal + ($subtotal * 0.05);
        $totalprice = number_format($total, 2);

        $cart_data = $user->getCartdata($user_id);
        
        foreach ($cart_data as $value) {
          if($user->minusProduct($value['pid'], $value['quantity'])) {
          } else {
            echo "nooooooo";
          }
        }

        $count_items = count($cart_data);


        if($user->addOrder($count_items, $totalprice, $user_address, $user_id)) {
          $getorderid = $user->getorderid($user_id);
          $order_id = $getorderid['order_id'];
          if($user->updateOrderid($order_id, $user_id)) {
            $user->redirect('thankyou.php');
          } else {
            $error[] = "Sorry, something went wrong with payment.";
          }
        } else {
          $error[] = "Sorry, something went wrong with payment.";
        }

        } // CHECK EMPTY

      }



?>



<!DOCTYPE html>
<!-- Created By CodingLab - www.codinglabweb.com -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title> Proceed payment </title>

    <link rel="stylesheet" href="../css/slide_payment.css">
    <link rel="stylesheet" type="text/css" href="../css/payment.css">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <script type="text/javascript" src="payment.js"></script> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


    	<?php include 'include/homestyle.php'; ?>

   </head>
<body>

	<?php include 'include/header.php'; ?>


<div class="container">

  <div class="content">
<!--     <div class='usetting_fail' style='margin-top: -10px;'> <i class='fas fa-times-circle'></i> <span> Sorry, something went wrong with payment. </span> </div> -->
    <?php if(isset($cardErr)) {
      foreach ($cardErr as $err) {
        echo "<div class='usetting_fail'> <i class='fas fa-times-circle'></i> <span>". 
        $err. ' </span> </div>';
      }
      }
    ?>

      <div class="tab-show">
        <div class="tab-show__inner">
          <!-- Button Container-->
          <div class="tab-show__inner__button-container" id="active_bar">
            <!-- Loop: Create the Buttons  -->
            <div class="tab-show__inner__button-container--button">
              <label class="tab-show__inner__button-container--button__label btn active_bar_active" for="tab-show-item-0"> 
                <i class="fa-solid fa-credit-card"></i>  Credit Card
              </label>
            </div>
            <div class="tab-show__inner__button-container--button">
              <label class="tab-show__inner__button-container--button__label btn" for="tab-show-item-1">
                <i class="fa-solid fa-building-columns"></i>  Online Banking
              </label>
            </div>
            <div class="tab-show__inner__button-container--button">
              <label class="tab-show__inner__button-container--button__label btn" for="tab-show-item-2">
                <i class="fa-brands fa-cc-paypal"></i>  Pay Pal
              </label>
            </div>
            <div class="tab-show__inner__button-container--button">
              <label class="tab-show__inner__button-container--button__label btn" for="tab-show-item-3">
                <i class="fa-brands fa-google-play"></i>  Google Play
              </label>
            </div>

          </div>
          <!-- Loop: Create the Checkboxes + Images-->
          <input class="tab-show__inner--hidden-checkbox" type="radio" name="tab-show" id="tab-show-item-1" checked=""/>
          <div class="tab-show__inner--image-container">
            <p class="no_payment">  
                 Sorry, Online Banking is not supported. 
            </p>
            <div class="tab-show__inner--image-container__image-floor"></div>
          </div>
          <input class="tab-show__inner--hidden-checkbox" type="radio" name="tab-show" id="tab-show-item-2" checked=""/>
          <div class="tab-show__inner--image-container">
            <p class="no_payment">  
                 Sorry,Pay Pal is not supported. 
            </p>
            <div class="tab-show__inner--image-container__image-floor"></div>
          </div>
          <input class="tab-show__inner--hidden-checkbox" type="radio" name="tab-show" id="tab-show-item-3" checked=""/>
          <div class="tab-show__inner--image-container">
            <p class="no_payment">  
                Sorry,Google Play is not supported. 
            </p>
            <div class="tab-show__inner--image-container__image-floor"></div>
          </div>


          <input class="tab-show__inner--hidden-checkbox" type="radio" name="tab-show" id="tab-show-item-0" checked="checked"/>
          <div class="tab-show__inner--image-container">


            <div class="payment_container">
             <div class="payment-title">
                    <h1>Payment Information</h1>
                </div>
                <div class="container preload ">
                    <div class="creditcard">
                        <div class="front">
                            <div id="ccsingle"></div>
                            <svg version="1.1" id="cardfront" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                x="0px" y="0px" viewBox="0 0 750 471" style="enable-background:new 0 0 750 471;" xml:space="preserve">
                                <g id="Front">
                                    <g id="CardBackground">
                                        <g id="Page-1_1_">
                                            <g id="amex_1_">
                                                <path id="Rectangle-1_1_" class="lightcolor grey" d="M40,0h670c22.1,0,40,17.9,40,40v391c0,22.1-17.9,40-40,40H40c-22.1,0-40-17.9-40-40V40
                                        C0,17.9,17.9,0,40,0z" />
                                            </g>
                                        </g>
                                        <path class="darkcolor greydark" d="M750,431V193.2c-217.6-57.5-556.4-13.5-750,24.9V431c0,22.1,17.9,40,40,40h670C732.1,471,750,453.1,750,431z" />
                                    </g>
                                    <text transform="matrix(1 0 0 1 60.106 295.0121)" id="svgnumber" class="st2 st3 st4">0123 4567 8910 1112</text>
                                    <text transform="matrix(1 0 0 1 54.1064 428.1723)" id="svgname" class="st2 st5 st6">Yan Bin</text>
                                    <text transform="matrix(1 0 0 1 54.1074 389.8793)" class="st7 st5 st8">cardholder name</text>
                                    <text transform="matrix(1 0 0 1 479.7754 388.8793)" class="st7 st5 st8">expiration</text>
                                    <text transform="matrix(1 0 0 1 65.1054 241.5)" class="st7 st5 st8">card number</text>
                                    <g>
                                        <text transform="matrix(1 0 0 1 574.4219 433.8095)" id="svgexpire" class="st2 st5 st9">11/28</text>
                                        <text transform="matrix(1 0 0 1 479.3848 417.0097)" class="st2 st10 st11">VALID</text>
                                        <text transform="matrix(1 0 0 1 479.3848 435.6762)" class="st2 st10 st11">THRU</text>
                                        <polygon class="st2" points="554.5,421 540.4,414.2 540.4,427.9    " />
                                    </g>
                                    <g id="cchip">
                                        <g>
                                            <path class="st2" d="M168.1,143.6H82.9c-10.2,0-18.5-8.3-18.5-18.5V74.9c0-10.2,8.3-18.5,18.5-18.5h85.3
                                    c10.2,0,18.5,8.3,18.5,18.5v50.2C186.6,135.3,178.3,143.6,168.1,143.6z" />
                                        </g>
                                        <g>
                                            <g>
                                                <rect x="82" y="70" class="st12" width="1.5" height="60" />
                                            </g>
                                            <g>
                                                <rect x="167.4" y="70" class="st12" width="1.5" height="60" />
                                            </g>
                                            <g>
                                                <path class="st12" d="M125.5,130.8c-10.2,0-18.5-8.3-18.5-18.5c0-4.6,1.7-8.9,4.7-12.3c-3-3.4-4.7-7.7-4.7-12.3
                                        c0-10.2,8.3-18.5,18.5-18.5s18.5,8.3,18.5,18.5c0,4.6-1.7,8.9-4.7,12.3c3,3.4,4.7,7.7,4.7,12.3
                                        C143.9,122.5,135.7,130.8,125.5,130.8z M125.5,70.8c-9.3,0-16.9,7.6-16.9,16.9c0,4.4,1.7,8.6,4.8,11.8l0.5,0.5l-0.5,0.5
                                        c-3.1,3.2-4.8,7.4-4.8,11.8c0,9.3,7.6,16.9,16.9,16.9s16.9-7.6,16.9-16.9c0-4.4-1.7-8.6-4.8-11.8l-0.5-0.5l0.5-0.5
                                        c3.1-3.2,4.8-7.4,4.8-11.8C142.4,78.4,134.8,70.8,125.5,70.8z" />
                                            </g>
                                            <g>
                                                <rect x="82.8" y="82.1" class="st12" width="25.8" height="1.5" />
                                            </g>
                                            <g>
                                                <rect x="82.8" y="117.9" class="st12" width="26.1" height="1.5" />
                                            </g>
                                            <g>
                                                <rect x="142.4" y="82.1" class="st12" width="25.8" height="1.5" />
                                            </g>
                                            <g>
                                                <rect x="142" y="117.9" class="st12" width="26.2" height="1.5" />
                                            </g>
                                        </g>
                                    </g>
                                </g>
                                <g id="Back">
                                </g>
                            </svg>
                        </div>
                        <div class="back">
                            <svg version="1.1" id="cardback" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                x="0px" y="0px" viewBox="0 0 750 471" style="enable-background:new 0 0 750 471;" xml:space="preserve">
                                <g id="Front">
                                    <line class="st0" x1="35.3" y1="10.4" x2="36.7" y2="11" />
                                </g>
                                <g id="Back">
                                    <g id="Page-1_2_">
                                        <g id="amex_2_">
                                            <path id="Rectangle-1_2_" class="darkcolor greydark" d="M40,0h670c22.1,0,40,17.9,40,40v391c0,22.1-17.9,40-40,40H40c-22.1,0-40-17.9-40-40V40
                                    C0,17.9,17.9,0,40,0z" />
                                        </g>
                                    </g>
                                    <rect y="61.6" class="st2" width="750" height="78" />
                                    <g>
                                        <path class="st3" d="M701.1,249.1H48.9c-3.3,0-6-2.7-6-6v-52.5c0-3.3,2.7-6,6-6h652.1c3.3,0,6,2.7,6,6v52.5
                                C707.1,246.4,704.4,249.1,701.1,249.1z" />
                                        <rect x="42.9" y="198.6" class="st4" width="664.1" height="10.5" />
                                        <rect x="42.9" y="224.5" class="st4" width="664.1" height="10.5" />
                                        <path class="st5" d="M701.1,184.6H618h-8h-10v64.5h10h8h83.1c3.3,0,6-2.7,6-6v-52.5C707.1,187.3,704.4,184.6,701.1,184.6z" />
                                    </g>
                                    <text transform="matrix(1 0 0 1 621.999 227.2734)" id="svgsecurity" class="st6 st7">666</text>
                                    <g class="st8">
                                        <text transform="matrix(1 0 0 1 518.083 280.0879)" class="st9 st6 st10">security code</text>
                                    </g>
                                    <rect x="58.1" y="378.6" class="st11" width="375.5" height="13.5" />
                                    <rect x="58.1" y="405.6" class="st11" width="421.7" height="13.5" />
                                    <text transform="matrix(1 0 0 1 59.5073 228.6099)" id="svgnameback" class="st12 st13">Yan Bin</text>
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>





                <form class="form-container" action="" method="post">
                    <div class="field-container">
                        <label for="name">Name</label>
                        <input id="name" maxlength="20" type="text" name="cardname">
                        <?php if(isset($cardErr1)) { echo "<span class='usetting_false'><i class='fas fa-exclamation-circle'></i>" . $cardErr1 . " </span>"; } ?>
                    </div>
                    <div class="field-container">
                        <label for="cardnumber">Card Number</label><span id="generatecard">Generate</span>
                        <input id="cardnumber" type="text" inputmode="numeric" name="cardnumber">
                        <?php if(isset($cardErr2)) { echo "<span class='usetting_false'><i class='fas fa-exclamation-circle'></i>" . $cardErr2 . " </span>"; } ?>
                        <svg id="ccicon" class="ccicon" width="750" height="471" viewBox="0 0 750 471" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">

                        </svg>
                    </div>
                    <div class="field-container">
                        <label for="expirationdate">Expiration (mm/yy)</label>
                        <input id="expirationdate" type="text" value="" name="expiration">
                        <?php if(isset($cardErr3)) { echo "<span class='usetting_false'><i class='fas fa-exclamation-circle'></i>" . $cardErr3 . " </span>"; } ?>
                    </div>
                    <div class="field-container">
                        <label for="securitycode">Security Code</label>
                        <input id="securitycode" type="text" pattern="[0-9]*" inputmode="numeric" name="securitycode">
                        <?php if(isset($cardErr4)) { echo "<span class='usetting_false'><i class='fas fa-exclamation-circle'></i>" . $cardErr4 . " </span>"; } ?>
                    </div>

                    <div class="field-container">
                    </div>
                    <div class="field-container">
                      <input type="submit" name="payment" value="Pay">
                    </div>

                </form>
            </div>



          </div>

          
        </div>
      </div>
  </div>
</div>



<script src='https://cdnjs.cloudflare.com/ajax/libs/imask/3.4.0/imask.min.js'></script><script  src="../js/script.js"></script>

</body>
</html>

<?php

    } else {
        $user->redirect("../index.php");
    }

	} else {
		$user->redirect("../index.php");
	} // if user_session didnt have email

} else {
	$user->redirect("../index.php");
}	// if user not login

?>

<script type="text/javascript">
  // Get the container element
  var btnContainer = document.getElementById("active_bar");

  // Get all buttons with class="btn" inside the container
  var btns = btnContainer.getElementsByClassName("btn");

  // Loop through the buttons and add the active class to the current/clicked button
  for (var i = 0; i < btns.length; i++) {
    btns[i].addEventListener("click", function() {
      var current = document.getElementsByClassName("active_bar_active");
      current[0].className = current[0].className.replace(" active_bar_active", "");
      this.className += " active_bar_active";
    });
  }
</script>