<?php 
if (count($thirdPartyCookiesAcceptances) == 0) {
    include('empty.php');
} else {
    // dump($showElements);
    // dump($thirdPartyCookiesAcceptances);exit;
    if ($detailedListRequest) {
        include('detailedList.php');
    } else {
        include('summarizer.php');
    }
}
?>