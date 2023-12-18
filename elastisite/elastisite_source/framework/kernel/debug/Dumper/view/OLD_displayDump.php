<div class="dump-frame">
    <div>
        <span class="dump-plain-text">
            File:
        </span>
        <span class="dump-type-string-value">
            <?php echo $file; ?>
        </span>
    </div>
    <div>
        <span class="dump-plain-text">
            Called at:
        </span>
        <span class="dump-plain-text">
            <span class="dump-class"><?php echo $callerClass; ?></span> /
            <span class="dump-method"><?php echo $callerFunction; ?>()</span>
            on line <span class="dump-type-string-value"><?php echo $line; ?></span>
        </span>
    </div>
    <div class="dump-hr"></div>
<?php
foreach ($blocks as $block) {

?>
    <div style="padding-left: <?php echo (int)$block['tab'] * 40; ?>px">
        <span class="dump-type-<?php echo $block['keyType']; ?>-key">
            <?php echo $block['key']; ?>
        </span>
        <span class="dump-type-<?php echo $block['valueType']; ?>-value">
            <?php echo $block['value']; ?>
        </span>
    </div>
<?php
}
?>
</div>
<?php if ($output == 'screen'): ?>
<style>
    .dump-frame {
    background-color: #282828 !important;
    color: #f4f4f4 !important;
    font: 16px Arial;
    margin: 5px;
    padding: 5px;
}

.dump-hr {
    height: 4px;
    background-color: #484848;
}

.dump-plain-text {
    color: #f4f4f4 !important;
}

.dump-class {
    color: #e7d965;
}

.dump-method {
    color: #7179e1;
}

.dump-type-string-key {
    color: #64e14f;
}

.dump-type-string-value {
    color: #64e14f;
}

.dump-type-element-key {
    color: #9ec4e4;
}

.dump-element-wrapper {
    color: #dedede;
}

/* .dump-type-element-key::before {
    color: #dedede;
    content:"[";
}

.dump-type-element-key::after {
    color: #dedede;
    content:"]";
} */

.dump-type-element-value {
    color: #6ba7d6;
}

.dump-type-array-key {
    color: #b7c1d8;
}

.dump-type-array-value {
    color: #859cd0;
}

.dump-type-object-key {
    color: #bd87bc;
}

.dump-type-object-value {
    color: #9d419c;
}

.dump-type-property-key {
    color: #e5a8e4;
}

.dump-type-property-value {
    color: #c766c6;
}

.dump-type-nullbool-value {
    color: #e2b988;
}

.dump-type-int-value {
    color: #5d78b5;
}
</style>
<?php endif; ?>