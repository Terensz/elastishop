<div class="widgetWrapper">
    <div class="article-head">
        <div class="article-title"><?php echo trans('setup.main.title'); ?></div>
    </div>
    <div class="article-content">
        <?php echo trans('setup.main.description'); ?>
    </div>
</div>

<form name="setup_form">

<div class="row formRow">
    <div class="col-lg-6 col-md-6">
        <div class="form-group">
            <button id="setup_refresh" style="width: 200px;"
                type="button" class="btn btn-secondary btn-block"
                onclick="SetupForm.refresh();"><?php echo trans('refresh'); ?></button>
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="form-group">
        </div>
    </div>
</div>

<?php 

//dump($opSup);

if ($databaseConnectionErrors):
?>
<div class="widgetWrapper">
    <div class="article-head">
        <div class="article-title"><?php echo trans('database.connection.errors'); ?></div>
    </div>
    <div class="article-content">
        <?php echo trans('database.connection.errors.description'); ?>
    </div>
    <div class="article-content-separator"></div>
    <div class="article-content">
<?php 
foreach ($databaseConnectionErrors as $databaseConnectionError):
?>
        <div><b><?php echo $databaseConnectionError; ?></b></div>
<?php 
endforeach
?>
    </div>
</div>
<?php 
endif
?>

<?php 
if ($missingCreateTableStatements):
?>
<div class="widgetWrapper">
    <div class="article-head">
        <div class="article-title"><?php echo trans('missing.create.table.statements'); ?></div>
    </div>
    <div class="article-content">
        <?php echo trans('missing.create.table.statements.description'); ?>
    </div>
    <div class="article-content-separator"></div>
    <div class="article-content">
<?php 
foreach ($missingCreateTableStatements as $missingCreateTableStatement):
?>
        <div><b><?php echo $missingCreateTableStatement; ?></b></div>
<?php 
endforeach
?>
    </div>
</div>
<?php 
endif
?>

<?php 
if ($missingTables):
?>
<div class="widgetWrapper">
    <div class="article-head">
        <div class="article-title"><?php echo trans('missing.tables'); ?></div>
    </div>
    <div class="article-content">
        <?php echo trans('missing.tables.description'); ?>
    </div>
    <div class="article-content-separator"></div>

    <div class="row formRow">
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <button id="setup_refresh" style="width: 200px;"
                    type="button" class="btn btn-secondary btn-block"
                    onclick="SetupForm.createTables();"><?php echo trans('create'); ?></button>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
            </div>
        </div>
    </div>

    <div id="createMissingTables" class="article-content">
<?php 
foreach ($missingTables as $missingTable):
?>
        <div><b><?php echo $missingTable; ?></b></div>
<?php 
endforeach
?>
    </div>
</div>
<?php 
endif
?>

<?php 
if ($databaseTableErrors):
?>
<div class="widgetWrapper">
    <div class="article-head">
        <div class="article-title"><?php echo trans('database.table.errors'); ?></div>
    </div>
    <div class="article-content">
        <?php echo trans('database.table.errors.description'); ?>
    </div>
    <div class="article-content-separator"></div>
    <div class="article-content">
        <?php dump($databaseTableErrors, ['header' => false]); ?>
    </div>
</div>
<?php 
endif
?>

<?php 
if ($unwritableDynamicFiles):
?>
<div class="widgetWrapper">
    <div class="article-head">
        <div class="article-title"><?php echo trans('unwritable.dynamic.files'); ?></div>
    </div>
    <div class="article-content">
        <?php echo trans('unwritable.dynamic.files.description'); ?>
    </div>
    <div class="article-content-separator"></div>
    <div class="article-content">
        <?php echo trans('unwritable.dynamic.files.count').': <strong>'.count($unwritableDynamicFiles).'</strong>'; ?>
    </div>
</div>
<?php 
endif
?>

<?php 
if ($writablePublicDirs):
?>
<div class="widgetWrapper">
    <div class="article-head">
        <div class="article-title"><?php echo trans('writable.public.dirs'); ?></div>
    </div>
    <div class="article-content">
        <?php echo trans('writable.public.dirs.description'); ?>
    </div>
    <div class="article-content-separator"></div>
    <div class="article-content">
        <?php echo trans('writable.public.dirs.count').': <strong>'.count($writablePublicDirs).'</strong>'; ?>
    </div>
</div>
<?php 
endif
?>

<?php 
if ($writablePublicFiles):
?>
<div class="widgetWrapper">
    <div class="article-head">
        <div class="article-title"><?php echo trans('writable.public.files'); ?></div>
    </div>
    <div class="article-content">
        <?php echo trans('writable.public.files.description'); ?>
    </div>
    <div class="article-content-separator"></div>
    <div class="article-content">
        <?php echo trans('writable.public.files.count').': <strong>'.count($writablePublicFiles).'</strong>'; ?>

<?php 
    if (count($writablePublicFiles) < 20):
        foreach ($writablePublicFiles as $writablePublicFile):
?>
        <div><b><?php echo $writablePublicFile; ?></b></div>
<?php 
        endforeach;
    endif;
?>

    </div>

</div>
<?php 
endif;
?>

</form>

<script>
var SetupForm = {
    getParameters: function() {
        return {
            'refreshPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/setup/MainWidget',
            'refreshSelector': '#widgetContainer-mainContent',
            'createTablesPath': '<?php echo $container->getUrl()->getHttpDomain(); ?>/setup/createMissingTables',
            'createTablesSelector': '#createMissingTables'
        };
    },
    refresh: function() {
        var params = SetupForm.getParameters();
        var ajaxData = {};
        var form = $('#setup_form');
        ajaxData = form.serialize();
        $.ajax({
            'type' : 'POST',
            'url' : params.refreshPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                console.log(response);
                var params = SetupForm.getParameters();
                $(params.refreshSelector).html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
    createTables: function() {
        var params = SetupForm.getParameters();
        var ajaxData = {};
        // var form = $('#setup_form');
        // ajaxData = form.serialize();
        $.ajax({
            'type' : 'POST',
            'url' : params.createTablesPath,
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                console.log(response);
                var params = SetupForm.getParameters();
                $(params.createTablesSelector).html(response.view);
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    },
};
</script>
