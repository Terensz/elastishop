<?php  
// dump($menuItemRoutes);
// dump($offeredRoutes);
?>

<h2><?php echo trans('elements.of.menu'); ?></h2>
<div id="UserAreaMenuEditor-sortable">
<?php foreach ($menuItemRoutes as $route): ?>
<?php
        $routeName = $route['routeName'];
        $routeId = $route['routeName'] ? : ':'.str_replace('/', '.', $route['routePath']);
        $routePath = $route['routePath'];
        $title = trans($route['title']);
        $tagClass = 'tag-light';
        $addOrRemoveButtonOnClick = "UserAreaMenuEditor.removeFromMenu('".$routeName."', '".$routePath."');";
        $addOrRemoveButtonType = 'warning';
        $addOrRemoveButtonText = trans('remove.from.menu');
        $editButtonOnClick = "UserAreaMenuEditor.initEditTitle('".$routeId."', '".$title."');";
        $sortingClasses = " ui-sortable sortable UserAreaMenuEditor-sorting-item";
        include('routeCard.php');
?>
<?php endforeach; ?>
</div>
<?php if (count($offeredRoutes) > 0): ?>
<div style="height: 20px;"></div>
<h2><?php echo trans('assign.routes.to.menu'); ?></h2>
    <?php foreach ($offeredRoutes as $route): ?>
<?php
            $routeName = $route['routeName'];
            $routeId = $route['routeName'] ? : ':'.str_replace('/', '.', $route['routePath']);
            $routePath = $route['routePath'];
            $title = trans($route['title']);
            $tagClass = 'tag-ultraLight';
            $addOrRemoveButtonOnClick = "UserAreaMenuEditor.addToMenu('".$routeName."', '".$title."', '".$routePath."');";
            $addOrRemoveButtonType = 'success';
            $addOrRemoveButtonText = trans('add.to.menu');
            $editButtonOnClick = "";
            $sortingClasses = "";
            include('routeCard.php');
?>
    <?php endforeach; ?>
<?php endif; ?>
<?php  
// dump($menuItem);
?>
<script>
$( "#UserAreaMenuEditor-sortable").sortable({
    create: function(event, ui) {
        if (event.type != 'sortcreate') {
            UserAreaMenuEditor.sort(ui);
        }
    },
    stop: function(event, ui) {
        UserAreaMenuEditor.sort(ui);
    }
});
</script>
