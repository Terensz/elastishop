<?php 
if ($cancelAllowed) {
    // include('framework/packages/WebshopPackage/view/widget/');
    include('success.php');
} else {
    include('fail.php');
}
?>