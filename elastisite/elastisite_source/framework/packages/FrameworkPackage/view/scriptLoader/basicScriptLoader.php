<!-- <script src="/public_folder/plugin/jQuery/3.5.1/jquery-3.5.1.min.js"></script> -->
<script src="/public_folder/plugin/jQuery/3.7.1/jquery-3.7.1.min.js"></script>
<script src="/public_folder/plugin/jQueryUI/jquery-ui.js"></script>
<script src="/public_folder/plugin/popper/1.16.0/popper.min.js"></script>

<!-- <link href="/public_folder/plugin/Bootstrap/4.6.2/bootstrap.min.css" rel="stylesheet">
<script src="/public_folder/plugin/Bootstrap/4.6.2/bootstrap.min.js"></script> -->

<link href="/public_folder/plugin/Bootstrap/5.1.3/bootstrap.min.css" rel="stylesheet">
<!-- <script src="/public_folder/plugin/scrollar/scrollar.js"></script> -->

<link href="/public_folder/asset/LoadingHandler/loadingHandlerSpinner.css" rel="stylesheet">
<script src="/public_folder/asset/LoadingHandler/LoadingHandler.js"></script>
<script src="/public_folder/script/basics.js"></script>
<script src="/public_folder/plugin/jQueryMask/jquery.mask.js"></script>

<script type="text/javascript" src="/public_folder/plugin/Moment/moment.min.js"></script>
<link rel="stylesheet" href="/public_folder/plugin/Pikaday/pikaday.css">
<script src="/public_folder/plugin/Pikaday/pikaday.js"></script>

<link rel="stylesheet" href="/public_folder/plugin/Dashkit/assets/fonts/feather.css">
<link rel="stylesheet" href="/public_folder/plugin/Dashkit/assets/fonts/fontawesome.css">
<link rel="stylesheet" href="/public_folder/plugin/Dashkit/assets/fonts/material.css">
<link rel="stylesheet" href="/public_folder/plugin/Dashkit/assets/css/style.css" id="main-style-link">
<!-- <link rel="stylesheet" href="/public_folder/plugin/Dashkit/assets/css/additional_style.css" id="main-style-link"> -->

<?php 

?>
<link href="/font/loader.css?v=<?php echo time(); ?>" rel="stylesheet">
<link href="/public_folder/skin/Basic/css/skin.css?v=<?php echo time(); ?>" rel="stylesheet">
<link href="/public_folder/skin/Basic/css/lightbox.css?v=<?php echo time(); ?>" rel="stylesheet">
<!-- <link href="/public_folder/skin/Basic/css/miniplayer.css?v=<?php echo time(); ?>" rel="stylesheet"> -->
<div id="skinCssContainer"><?php
if (isset($skinName) && $skinName != 'Basic'):
?>
    <!-- <link href="/public_folder/skin/<?php echo $skinName; ?>/css/skin.css?v=<?php echo time(); ?>" rel="stylesheet"> -->
<?php
endif
?></div>