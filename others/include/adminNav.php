<!DOCTYPE html>
<html>
<head>
  <title>Netify</title>

  <?php include 'include/homestyle.php'; ?>

</head>
<body> 

<div class="sidenav" id="sidenav">
  <a href="#about" class="admin_logo" >Netify<span>&#9656;</span> </a>

  <!-- <a href="#about" class="btn adminNav_active"> <i class="far fa-user"></i>  Admin detail</a> -->
  <a href="admin_ulist.php" class="btn ">  <i class="fa-solid fa-users"></i>User</a>
  <a href="admin_panel.php" class="btn">   <i class="far fa-edit"></i>Edit</a>

  <a href="../index.php" class=" admin_back"> <i class="fas fa-chevron-circle-left"></i>Back</a>

</div>

</body>
</html>

<script type="text/javascript">
  // Get the container element
  var btnContainer = document.getElementById("sidenav");

  // Get all buttons with class="btn" inside the container
  var btns = btnContainer.getElementsByClassName("btn");

  // Loop through the buttons and add the active class to the current/clicked button
  for (var i = 0; i < btns.length; i++) {
    btns[i].addEventListener("click", function() {
      var current = document.getElementsByClassName("adminNav_active");
      current[0].className = current[0].className.replace(" adminNav_active", "");
      this.className += " adminNav_active";
    });
  }
</script>



<!--  IMAGE CAT --  0  -->
<script type="text/javascript">
  
    function showPreviewCat(event){
  if(event.target.files.length > 0){
    var src = URL.createObjectURL(event.target.files[0]);
    var preview = document.getElementById("file-cat-previewCat");
    preview.src = src;
    preview.style.display = "block";
  }
}

</script>