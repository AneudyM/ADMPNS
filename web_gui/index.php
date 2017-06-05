<!DOCTYPE html>
<html>
    <head>
        <title>ADMPNS</title>
        <link rel="stylesheet" type="text/css" href="style.css" media="screen">
    </head>
    <body>
        <div id="main">
            <?php include('include/inc_header.html'); ?><br>
            <?php include('include/inc_navmenu.html'); ?><br>
            <?php
                if (isset($_GET['content'])) {
                    switch ($_GET['content']) {
                        case 'Create new Network':
                            include ('include/inc_new_network.html');
                            break;
                        case 'View existing Networks':
                            include ('view_networks.php');
                            break;
                        default:
                            include ('include/inc_home.html');
                            break;
                    }
                }
            ?><br>
            <?php include ('include/inc_footer.html'); ?>
        </div>
    </body>
</html>