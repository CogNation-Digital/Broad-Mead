<?php
include "../../includes/config.php";
if(isset($_POST['EmailID'])){
   //Check if the email is sending...
   $EmailID = $_POST['EmailID'];

   $query = $conn->query("SELECT Status FROM `_emails` WHERE EmailID = '$EmailID'");
   $row = $query->fetchObject();
   //echo $row->Status;

   echo "Sending...";
}
?>




