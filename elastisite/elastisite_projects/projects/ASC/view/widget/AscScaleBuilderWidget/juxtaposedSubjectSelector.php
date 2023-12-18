<?php foreach ($primarySubjectConfig as $primarySubject => $primarySubjectConfigRow): ?>
    <?php if ($primarySubject != $currentSubject): ?>

<div href="/asc/scaleBuilder/scale/<?php echo $ascScale->getId(); ?>/subject/<?php echo $currentSubject ?>/juxtaposedSubject/<?php echo $primarySubject ?>">
    <div id="<?php echo $primarySubject; ?>" class="ajaxCaller PrimarySubject-listItem PrimarySubjectBar-subject">
        <?php 
            echo trans($primarySubjectConfigRow['translationReferencePlural']); 
        ?>
    </div>
</div>

<script>
    $('document').ready(function() {
        $('.PrimarySubject-listItem').on('click', function() {
            console.log('klik');
        });
    });
</script> 
    <?php endif; ?>
<?php endforeach; ?>