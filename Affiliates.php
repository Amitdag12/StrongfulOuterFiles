<?php
session_start();
if($_SESSION["UserType"]!=="affiliate")
{
  //header("Location: http://www.google.com");
die();
}


?>
<html>
<head>
<h1>  affiliate </h1>
</head>
<body>

   <div>
     <div>
       <h3> Your balance </h3>
       <br>
       <?php
       echo $_SESSION["UserBalance"];
       ?>
     </div>
     <div>
       <h3> Your code </h3>
       <br>
       <?php
       echo $_SESSION["UserCode"];
       ?>
     </div>
   </div>
</body>
</html
