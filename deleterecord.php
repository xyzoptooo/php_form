 <?php
    require_once("dbs.php");

    $id=$_REQUEST['id']; //recieve the primary keyof the selected record

    $del=mysqli_query($myconn, "DELETE FROM ticket_orders WHERE id='$id'");

    if($del)
    {
        echo "Record Deleted Successfully";
    }
    else 
        echo "Record Not Deleted";
     

        echo "<a href='view_tickets.php'> Go Back To Records List</a>";

?>