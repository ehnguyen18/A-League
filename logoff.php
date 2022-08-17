<!--20000820-->
<!--Pham Gia Huy Nguyen -->
<!--Wednesday 9:00AM-->
<?php
   require_once("nocache.php");
   session_start();
   session_destroy();
   header("location: index.php");
?>