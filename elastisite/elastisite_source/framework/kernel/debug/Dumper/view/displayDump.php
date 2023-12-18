<div class="dump-frame" style="background-color: #282828 !important; 
    color: #f4f4f4 !important;
    font: 16px Arial;
    margin: 5px;
    padding: 5px;">
    <div>
        <span class="dump-plain-text" style="color: #f4f4f4 !important;">
            File:
        </span>
        <span class="dump-type-string-value" style="color: #64e14f;">
            <?php echo $file; ?>
        </span>
    </div>
    <div>
        <span class="dump-plain-text" style="color: #f4f4f4 !important;">
            Called at:
        </span>
        <span class="dump-plain-text" style="color: #f4f4f4 !important;">
            <span class="dump-class" style="color: #e7d965;"><?php echo $callerClass; ?></span> /
            <span class="dump-method" style="color: #7179e1;"><?php echo $callerFunction; ?>()</span>
            on line <span class="dump-type-string-value" style="color: #64e14f;"><?php echo $line; ?></span>
        </span>
    </div>
    <div class="dump-hr" style="height: 4px; background-color: #484848;"></div>
<?php
include 'displayDumpElement.php'; 
?>
</div>
