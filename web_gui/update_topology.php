<?php
require_once("include/inc_database_info.php");

if (isset($_GET['edit'])){
    $id=$_GET['edit'];
    $sql="Select * from TOPOLOGY where topologyId=$id";
    $queryResult = $DBConnect->query($sql);
    $row = $queryResult->fetch_array();
}

if(isset($_POST['updateName'])){
    $newName = $_POST['updateName'];
    $getID = $_POST['getID'];
    $sqlupdate = "UPDATE TOPOLOGY set topologyName='$newName' where topologyId='$getID'";
    $result = $DBConnect->query($sqlupdate);
    
}

?>


<link rel="stylesheet" type="text/css" href="style.css" media="screen">
<div id="">
<form action="update_topology.php" method="post">
    Topology Name: <input type="text" name="updateName" value="<?php echo 
    $row["topologyName"];?> "> <br>
    <input type="hidden" name="getID" value="<?php echo 
    $row["topologyId"];?>">
    <input type="submit" value="Update Value">    
</form>
    
</div>

<?php
$DBConnect->close();
?>