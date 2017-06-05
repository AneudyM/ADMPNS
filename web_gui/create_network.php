
<?php 
    require_once("include/inc_database_info.php");
    require_once("include/library.php");

?>
<link rel="stylesheet" type="text/css" href="style.css" media="screen">
<?php
if ($DBConnect->connect_errno){
    echo "<p> Unable to connect to DB. </p>"
    ."<p> Error Code ".$DBConnect->connect_errno
    .": ". $DBConnect->connect_error . "</p>\n";
}
?>

<div id="main">
<?php
session_start();

    # get the network owner and description from form
    $networkOwner = $_POST['networkOwner'];
    $networkDescription = $_POST['networkDescription'];
    $availablePublicIP = getNextPublicIP($DBConnect);
     
    # create a database statment with information from fields
    $lastNetworkID = createNetwork($DBConnect, $networkOwner, $networkDescription, $availablePublicIP);
    $_SESSION['networkID'] = $lastNetworkID;
    # Make the assigned IP unavailable
    updatePublicIPStatus($lastNetworkID,$DBConnect);
    
    include ('include/inc_header.html');
    echo "<br>";
    echo "The network <b>".$networkOwner."</b> has been successfully created.";
    echo "<br>";
    echo "Description: <b>".$networkDescription."</b>.";
    include ('include/inc_questionnaire.html');
?>
</div>

<?php $DBConnect->close(); ?> 
