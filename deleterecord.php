
<?php
require_once("dbs.php");

if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {

    
  $id = (int)$_REQUEST['id']; //make the id to always integer
   
  $statment = $myconn->prepare("DELETE FROM ticket_orders WHERE id = ?");

      $statment->bind_param("i", $id);
 
    if ($statment->execute()) {      //method uded with prepared statments --run the prepared sql statement 
        echo "Record Deleted Successfully";
    } else {
        echo "Record Not Deleted";
    }
    $statment->close();
} else {
    echo "Invalid or missing record ID.";
}

echo "<a href='view_tickets.php'> Go Back To Records List</a>";
?>