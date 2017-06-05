<?php
require_once("include/inc_database_info.php");
require_once("include/library.php");
?>
<link rel="stylesheet" type="text/css" href="style.css" media="screen">
<div id="main">
<?php include('include/inc_header.html') ?><br>
<h3>View Nodes in the Topology</h3>
<?php
$topologyID = $_GET['topologyID'];

    $sqlstring = "Select topologyName from TOPOLOGY where topologyId='$topologyID'"; 
    $queryResult = $DBConnect->query($sqlstring); 
    $sqlTopology = "select nodeId, hostname, state from NODE where TOPOLOGY_topologyId='$topologyID'";
    $queryTopology = $DBConnect->query($sqlTopology);
    

while ($row = $queryResult->fetch_assoc()){
        echo "<p> Topology:  ".$row ["topologyName"]."</p>";
    }
    echo "<table id='tableNodes' border='1' cellpadding='5' align='center'>\n <tr>"
        . "<th>ID</th>"
        . "<th>Hostname</th>"
        . "<th>IP Address</th>"
        . "<th>Active</th></tr>";
    
    while ($rowT = $queryTopology->fetch_assoc()){
        $nodeID = $rowT["nodeId"];
        $nodeIP = getNodeIP($nodeID, $DBConnect);
    echo"<tr><td>" .$nodeID. "</td>
        <td>" . $rowT["hostname"]. "</td>
        <td>" . $nodeIP. "</td> 
        <td>" . $rowT["state"]. "</td></tr>\n";
    }
    
    echo "</table>";
?>
 <br><br>
<?php include('include/inc_footer.html') ?>
</div>
<?php
$DBConnect->close();
?>

