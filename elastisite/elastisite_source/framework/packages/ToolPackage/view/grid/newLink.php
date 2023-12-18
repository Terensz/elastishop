<?php
if ($grid->getAllowCreateNew()) {
?>
<a href="" class="triggerModal" onClick="<?php echo ucfirst($grid->getGridName()); ?>Grid.new();"><?php echo trans('create.new'); ?></a>
<br />
<?php
}
?>
