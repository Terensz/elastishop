<?php if ($tableData['urls']['newActionUrl']): ?>

<div class="newItem mb-4">
    <button id="AscScaleLister_newScale" onclick="<?php echo $tableData['data']['dataGridId']; ?>.new(event);" class="btn btn-success"><?php echo $tableData['texts']['createNewText']; ?></button>
</div>

<!-- <div class="newItem">
    <a class="triggerModal" href="" onclick="<?php echo $tableData['data']['dataGridId']; ?>.new(event);"><?php echo $tableData['texts']['createNewText']; ?></a>
</div> -->
<?php endif; 

//dump($totalPages);
?>