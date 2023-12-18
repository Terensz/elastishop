<?php 

$viewPath = ($this->getSession()->userLoggedIn())
? ($this->isGranted('viewTokenRequiredContent')
    ? 'framework/packages/UserPackage/view/widget/LoginWidget/token.php'
    : 'framework/packages/UserPackage/view/widget/LoginWidget/userDetails.php')
: 'framework/packages/UserPackage/view/widget/LoginWidget/login.php';

include($viewPath);

?>