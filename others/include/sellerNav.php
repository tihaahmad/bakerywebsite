<!DOCTYPE html>
<html>
<head>
  <title>Netify</title>

  <?php include 'include/homestyle.php'; ?>

</head>
<body> 
  </div>

</body>
</html>
<script>
        $(window).on("load",function(){
          $(".container1").fadeOut(950);
        });
</script>

<script type="text/javascript">
  // Get the container element
  var btnContainer = document.getElementById("SellerNav");

  // Get all buttons with class="btn" inside the container
  var btns = btnContainer.getElementsByClassName("btn");

  // Loop through the buttons and add the active class to the current/clicked button
  for (var i = 0; i < btns.length; i++) {
    btns[i].addEventListener("click", function() {
      var current = document.getElementsByClassName("sellerNav_active");
      current[0].className = current[0].className.replace(" sellerNav_active", "");
      this.className += " sellerNav_active";
    });
  }
</script>
