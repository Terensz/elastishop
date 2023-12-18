<?php 
$classStrActiveAdd = $currentSubject == $subject ? '-active' : '';
$urlJuxtaposedSubjectStr = $juxtaposedSubject && $juxtaposedSubject != $subject ? '/juxtaposedSubject/'.$juxtaposedSubject : '';
?>
<div href="/asc/scaleBuilder/scale/<?php echo $ascScale->getId(); ?>/subject/<?php echo $subject; ?><?php echo $urlJuxtaposedSubjectStr; ?>">
    <div class="ajaxCaller PrimarySubjectBar-subject<?php echo $classStrActiveAdd; ?>">
        <?php echo trans($config['translationReferencePlural']); ?>
    </div>
</div>