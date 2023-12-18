<?php 

if (App::getContainer()->isGranted('viewStaffMemberContent')) {
    include('staffMemberContent.php');
} else {
    include('loginGuide.php');
}

?>