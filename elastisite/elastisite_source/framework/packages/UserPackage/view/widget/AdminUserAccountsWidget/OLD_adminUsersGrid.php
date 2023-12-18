<style>
.grid-title-row {
    border-top: 1px solid #c0c0c0;
    border-right: 1px solid #c0c0c0;
    border-bottom: 1px solid #c0c0c0;
}

.grid-title-cell {
    background-color: #ededed;
    padding: 8px;
    border-left: 1px solid #c0c0c0;
}

.grid-body-row {
    border-right: 1px solid #c0c0c0;
    border-bottom: 1px solid #c0c0c0;
    cursor: pointer;
}

.grid-body-cell {
    padding: 8px;
    border-left: 1px solid #c0c0c0;
}
</style>

<div class="grid-container">
<?php
echo $grid;
?>
</div>

<script>
var <?php echo ucfirst($grid->getGridName()); ?>Grid = {
    new: function() {
        $('#editorModalLabel').html('');
        $('#editorModalBody').html('');
        <?php echo ucfirst($grid->getGridName()); ?>GridAjaxInterface.call(null);
        $('#editorModal').modal('show');
    },
    edit: function(id) {
        $('#editorModalLabel').html('');
        $('#editorModalBody').html('');
        <?php echo ucfirst($grid->getGridName()); ?>GridAjaxInterface.call(id);
        $('#editorModal').modal('show');
    },
    delete: function(id) {
        if (id == undefined || id === null || id === false) {
            return false;
        }
        $('#confirmModalConfirm').attr('onClick', "<?php echo ucfirst($grid->getGridName()); ?>Grid.deleteConfirmed(" + id + ");");
        $('#confirmModal').modal('show');
    },
    deleteConfirmed: function(id) {
        <?php echo ucfirst($grid->getGridName()); ?>GridAjaxInterface.delete(id);
        $('#confirmModal').modal('hide');
    }
};

$('body').on('click', '.triggerModal', function (e) {
    e.preventDefault();
});
</script>
