<?php
session_destroy();
      unset($_SESSION['user_session']); 
      return true;
?>