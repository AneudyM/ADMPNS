<?php
    
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

# ------------------------------------------------------------------------------
    function getNextPublicIP($DBConnect) {
        $sql = "SELECT publicIP FROM PUBLIC_IP_POOL WHERE available = 1 LIMIT 1";
        $execute = $DBConnect->query($sql);
        $nextPrivateIP = $execute->fetch_array(MYSQLI_NUM);
        return $nextPrivateIP[0];
    }
    
    function getNextPrivateIP($DBConnect) {
        $sql = "SELECT privateIP FROM PRIVATE_IP_POOL WHERE available = 1 LIMIT 1";
        $execute = $DBConnect->query($sql);
        $nextPrivateIP = $execute->fetch_array(MYSQLI_NUM);
        return $nextPrivateIP[0];
    }
    
    function updatePublicIPStatus($networkID,$DBConnect) {
        $sql = "UPDATE PUBLIC_IP_POOL 
                   SET available = 0
                 WHERE publicIP = (SELECT publicIPAddress 
                                     FROM NETWORK 
                                    WHERE networkId = $networkID)";
        $execute = $DBConnect->query($sql);
    }
    
    function updatePrivateIPStatus($topologyID,$DBConnect){
        $sql = "UPDATE PRIVATE_IP_POOL 
                   SET available = 0
                 WHERE privateIP = (SELECT topologyIP 
                                     FROM TOPOLOGY
                                    WHERE topologyId = $topologyID)";
        $execute = $DBConnect->query($sql);
    }
/*
 *  NETWORK STRUCTURE FUNCTIONS
 */
    function createNetwork($DBConnect,$networkOwner,$networkDescription,$availablePublicIP){
         $sql = "INSERT INTO NETWORK (
                                networkOwner,
                                networkDescription,
                                publicIPAddress
                           ) VALUES (
                                '$networkOwner',
                                '$networkDescription',
                                '$availablePublicIP'
                           )";
        $execute = $DBConnect->query($sql);
        $networkID  = $DBConnect->insert_id;
        print $networkID;
        return $networkID;
    }
    
    function createTopology($DBConnect,$topology_name,$nextPrivateIP,$topology_description,$networkID){
        # Create topology
        $sql = "INSERT INTO TOPOLOGY (
                       topologyName,
                       topologyIP,
                       topologyNetmask,
                       topologyStatus,
                       topologyDescription,
                       NETWORK_networkId
                ) VALUES (
                       '$topology_name',
                       '$nextPrivateIP',
                       '255.255.255.0',
                       1,
                       '$topology_description',
                       $networkID
                )";
        $execute = $DBConnect->query($sql);
        $topologyID = $DBConnect->insert_id;
        return $topologyID;
    }
      
/*
 *  FUNCTIONS FOR THE CREATION OF NODES
 */
    function createWorkstation($DBConnect,$hostname,$networkID,$topologyID){
        # Creating a Workstation node
        $sql = "INSERT INTO NODE (
                       hostname,
                       state,
                       NODE_TYPE_nodeTypeId,
                       TOPOLOGY_topologyId,
                       TOPOLOGY_NETWORK_networkId
              ) VALUES (
                       '$hostname',
                       1,
                       5,
                       '$topologyID',
                       '$networkID'
            )";
        $execute = $DBConnect->query($sql);
        $routerID = $DBConnect->insert_id;
        return $routerID;
    }
    
    function createRouter($DBConnect,$hostname,$networkID,$topologyID){
        # Creating a Router node
        $sql = "INSERT INTO NODE (
                       hostname,
                       state,
                       NODE_TYPE_nodeTypeId,
                       TOPOLOGY_topologyId,
                       TOPOLOGY_NETWORK_networkId
                )VALUES(
                       '$hostname',
                       1,
                       1, 
                       '$topologyID',
                       '$networkID'
                )";
        $execute = $DBConnect->query($sql);
        $routerID = $DBConnect->insert_id;
        return $routerID;
    }
   
    function createSwitch($hostname,$DBConnect,$topologyID,$networkID){
        $sql = "INSERT INTO NODE (
                       hostname,
                       state,
                       NODE_TYPE_nodeTypeId,
                       TOPOLOGY_topologyId,
                       TOPOLOGY_NETWORK_networkId
               )VALUES(
                       '$hostname',
                       1,
                       2,
                       '$topologyID',
                       '$networkID'
                )";
        $execute = $DBConnect->query($sql);
        $switchID = $DBConnect->insert_id;
        return $switchID;
    }
    
    function createFirewall($hostname,$DBConnect,$topologyID,$networkID){
        $sql = "INSERT INTO NODE (
                       hostname,
                       state,
                       NODE_TYPE_nodeTypeId,
                       TOPOLOGY_topologyId,
                       TOPOLOGY_NETWORK_networkId
               )VALUES(
                       '$hostname',
                       1,
                       3,
                       '$topologyID',
                       '$networkID'
                )";
        $execute = $DBConnect->query($sql);
        $firewallID = $DBConnect->insert_id;
        return $firewallID;
    }
    
    function createService($hostname,$DBConnect,$topologyID,$networkID){
        $sql = "INSERT INTO NODE (
                       hostname,
                       state,
                       NODE_TYPE_nodeTypeId,
                       TOPOLOGY_topologyId,
                       TOPOLOGY_NETWORK_networkId
               )VALUES(
                       '$hostname',
                       1,
                       4,
                       '$topologyID',
                       '$networkID'
                )";
        $execute = $DBConnect->query($sql);
        $switchID = $DBConnect->insert_id;
        return $switchID;
    }
    
    function getNodeTypeIdByName($nodeTypeName,$DBConnect) {
        $sql = "SELECT nodeTypeId
                  FROM NODE_TYPE
                 WHERE nodeTypeName = $nodeTypeName";
        $execute = $DBConnect->query($sql);
        $nodeType = $execute->fetch_array(MYSQLI_NUM);
        return $nodeType[0];
    }
    
    function attachInterfaceTo($nodeID,$neighborNodeID,$DBConnect,$ipAddress){
        # Adding interface to a node
        $sql = "INSERT INTO INTERFACE (
                       int_ip_address,
                       int_netmask,
                       int_state,
                       NODE_nodeId,
                       neighbor_nodeId
              ) VALUES (
                       '$ipAddress',
                       '255.255.255.0',
                       1,
                       '$nodeID',
                       '$neighborNodeID'
              )";
        $execute = $DBConnect->query($sql);
        $interfaceID = $DBConnect->insert_id;
        return $interfaceID;
    }
    
    function updateRoutingTable(){
        
    }
    
    function addToRoutingTable(){
        
    }
    
    function deleteFromRoutingTable(){
        
    }
    
    function createVlan($DBConnect,$departmentName,$ipAddress,$switchID){
        $insertVlan = "INSERT INTO VLAN (
                       vlan_description,
                       vlan_ip_address,
                       NODE_nodeId
               )VALUES(
                       '$departmentName',
                       '$ipAddress',
                       '$switchID'
               )";
        $executeInsert = $DBConnect->query($insertVlan);
        $vlanID = $DBConnect->insert_id;
        $queryIp = "SELECT vlan_ip_address FROM VLAN WHERE vlanId = $vlanID";
        $executeSelect = $DBConnect->query($queryIp);
        $vlanIP = $executeSelect->fetch_array(MYSQLI_NUM);
        return $vlanIP;
    }
    
    function attachLinkTo($DBConnect){
        
    }
            
    function getTopologyIP($topologyID,$DBConnect) {
        $sql = "SELECT topologyIP
                  FROM TOPOLOGY 
                 WHERE topologyId = $topologyID";
        $execute = $DBConnect->query($sql);
        $topologyIP = $execute->fetch_array(MYSQLI_NUM);
        return $topologyIP[0];
    }
    
    function getNodeIP($nodeID,$DBConnect) {
        $sql = "SELECT int_ip_address
                  FROM INTERFACE 
                 WHERE NODE_nodeId = $nodeID";
        $execute = $DBConnect->query($sql);
        $int_ip_address = $execute->fetch_array(MYSQLI_NUM);
        return $int_ip_address[0];
    }
    
    

