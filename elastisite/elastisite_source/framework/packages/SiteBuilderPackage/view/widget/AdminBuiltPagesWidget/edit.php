<div>
    <div class="row tabs">
        <div href="" data-tabid="propertiesView" onclick="EditBuiltPageModal.switchTab(event, 'propertiesView');" class="col-lg-6 editBuiltPagesTab editBuiltPagesTab-propertiesView tab-active doNotTriggerHref">
            <a class="doNotTriggerHref" href=""><?php echo trans('webpage.properties'); ?></a>
        </div>
        <?php if ($form->getEntity()->getId()): ?>
        <div href="" data-tabid="assemblerView" onclick="EditBuiltPageModal.switchTab(event, 'assemblerView');" 
            class="col-lg-6 editBuiltPagesTab editBuiltPagesTab-assemblerView tab-inactive doNotTriggerHref">
            <a class="doNotTriggerHref" href=""><?php echo trans('webpage.assembler'); ?></a>
        </div>
        <?php else: ?>
        <div href="" data-tabid="assemblerView" onclick="" 
            class="col-lg-6 tab-inactive doNotTriggerHref">
            <?php echo trans('webpage.assembler'); ?>
        </div>
        <?php endif; ?>
    </div>
    <div id="editBuiltPage-propertiesView-container">
<?php

$formView = $viewTools->create('form')->setForm($form);
$formView->setResponseLabelSelector('#editorModalLabel');
$formView->setResponseBodySelector('#editorModalBody');

$formView->add('text')->setPropertyReference('routeName')->setLabel(trans('route.name'));
$formView->add('custom')->setPropertyReference(null)->setLabel(null)->addCustomData('view', $editableBuiltInRouteSelectorView);
$formView->add('text')->setPropertyReference('title')->setLabel(trans('title'));

/**
 * numberOfPanelsSelect
*/
// $numberOfPanelsSelect = $formView->add('select')
//     ->setPropertyReference('numberOfPanels')
//     ->setLabel(trans('number.of.panels'));
// foreach (['1' => 1, '2' => 2] as $isMenuItemKey => $isMenuItemValue) {
//     $numberOfPanelsSelect->addOption($isMenuItemValue, $isMenuItemKey);
// }

// $formView->add('custom')->setPropertyReference(null)->setLabel(null)->addCustomData('view', '
// <div class="row">
// Alma
// </div>
// ');


// structure
// $structureSelect = $formView->add('select')
//     ->setPropertyReference('structure')
//     ->setLabel(trans('structure'));
// $structureSelect->addOption('*null*', '-');

// foreach ($structures as $structure) {
//     $structureSelect->addOption($background->getTheme(), $background->getTitle());
// }

/**
 * isMenuItemSelect
*/
// $isMenuItemSelect = $formView->add('select')
//     ->setPropertyReference('isMenuItem')
//     ->setLabel(trans('is.menu.item'));
// foreach (['true' => 1, 'false' => 0] as $isMenuItemKey => $isMenuItemValue) {
//     $isMenuItemSelect->addOption($isMenuItemValue, $isMenuItemKey);
// }


// $routeNameSelect = $formView->add('select')
//     ->setPropertyReference('routeName')
//     ->setLabel(trans('route.name'));

// foreach ($routeMap as $routeMapElement) {
//     if (isset($routeMapElement['title'])) {
//         // $selectedStr = $pageBackground->getRouteName() == $routeMapElement['name'] ? ' selected' : '';
//         $routeNameSelect->addOption(
//             $routeMapElement['name'], 
//             $container->getRoutingHelper()->getObviousParamChain($routeMapElement['paramChains'])
//             .' - ('.trans($routeMapElement['title']).')', 
//             false
//         );
//     }
// }

// $backgroundNameSelect = $formView->add('select')
//     ->setPropertyReference('fbsBackgroundTheme')
//     ->setLabel(trans('background.name'));
// $backgroundNameSelect->addOption('*null*', '-');

// foreach ($backgrounds as $background) {
//     $backgroundNameSelect->addOption($background->getTheme(), $background->getTitle());
// }

// $formView->add('color')->setPropertyReference('backgroundColor')->setLabel(trans('background.color'));

// $formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));
$formView->add('submit')->setPropertyReference('submit')->setValue(trans('save'));
$formView->setFormMethodPath('admin/builtSite/edit');
$formView->displayForm()->displayScripts();

?>
    </div>
    <div id="editBuiltPage-assemblerView-container">
    </div>
</div>

<script>
    var EditBuiltPageModal = {
        switchTab: function(e, tabId) {
            if (e != null) {
                e.preventDefault();
            }

            $('.editBuiltPagesTab').each(function() {
                let loopTabid = $(this).attr('data-tabid');
                // console.log('loopTabid:', loopTabid);
                if (loopTabid == tabId) {
                    $(this).addClass('tab-active');
                    $(this).removeClass('tab-inactive');
                    $('#editBuiltPage-' + loopTabid + '-container').show();

                    // Custom mukodes
                    if (loopTabid == 'assemblerView') {
                        DistantViewEditor.loadBase();
                    }
                } else {
                    $(this).addClass('tab-inactive');
                    $(this).removeClass('tab-active');
                    $('#editBuiltPage-' + loopTabid + '-container').hide();
                }
            });
        },
        showDedicatedRoutes: function(e) {
            e.preventDefault();
            EditBuiltPageModal.hideRouteNameInput();
            $('#editBuiltPage-editableRoutes-showDedicatedRoutes').hide();
            $('#editBuiltPage-editableRoutes-hideDedicatedRoutes').show();
            $('#editBuiltPage-editableRoutes-container').show();
        },
        hideDedicatedRoutes: function(e) {
            e.preventDefault();
            EditBuiltPageModal.showRouteNameInput();
            $('#editBuiltPage-editableRoutes-showDedicatedRoutes').show();
            $('#editBuiltPage-editableRoutes-hideDedicatedRoutes').hide();
            $('#editBuiltPage-editableRoutes-container').hide();
        },
        showRouteNameInput: function(e) {
            $('#SiteBuilderPackage_editBuiltPage_routeName').parent().parent().parent().parent().show();
        },
        hideRouteNameInput: function(e) {
            $('#SiteBuilderPackage_editBuiltPage_routeName').parent().parent().parent().parent().hide();
        }
    };

    var PageProperties = {
        selectDedicatedPage: function() {
            
        }
    };

    var DistantViewEditor = {
        sortableListenerInitialized: false,
        builtPageId: '<?php echo $form->getEntity()->getId(); ?>',
        loadBase: function() {
            // console.log('AssemblerView.load ' + id);
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/admin/pageAssembler/distantViewEditor/base',
                'data': {
                    'builtPageId': DistantViewEditor.builtPageId
                },
                'async': false,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    // console.log(response);
                    $('#editBuiltPage-assemblerView-container').html(response.view);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                    // LoadingHandler.stop();
                },
            });
        },
        addLeftPanel: function(e) {
            e.preventDefault();
            // console.log('AssemblerView.load ' + id);
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/admin/pageAssembler/distantViewEditor/addLeftPanel',
                'data': {
                    'builtPageId': DistantViewEditor.builtPageId,
                },
                'async': false,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    DistantViewEditor.loadBase();
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                    // LoadingHandler.stop();
                },
            });
        },
        removeLeftPanel: function(e) {
            e.preventDefault();
            // console.log('AssemblerView.load ' + id);
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/admin/pageAssembler/distantViewEditor/removeLeftPanel',
                'data': {
                    'builtPageId': DistantViewEditor.builtPageId
                },
                'async': false,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    DistantViewEditor.loadBase();
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                    // LoadingHandler.stop();
                },
            });
        },
        addWidget: function(e, position, widgetName) {
            e.preventDefault();
            // console.log('AssemblerView.load ' + id);
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/admin/pageAssembler/distantViewEditor/addWidget',
                'data': {
                    'builtPageId': DistantViewEditor.builtPageId,
                    'position': position,
                    'widgetName': widgetName
                },
                'async': false,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    DistantViewEditor.loadBase();
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                    // LoadingHandler.stop();
                },
            });
        },
        removeWidget: function(e, position, widgetName) {
            e.preventDefault();
            // console.log('AssemblerView.load ' + id);
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/admin/pageAssembler/distantViewEditor/removeWidget',
                'data': {
                    'builtPageId': DistantViewEditor.builtPageId,
                    'position': position,
                    'widgetName': widgetName
                },
                'async': false,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    DistantViewEditor.loadBase();
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                    // LoadingHandler.stop();
                },
            });
        },
        sortLeftWidgets: function(ui) {
            return DistantViewEditor.sortWidgets('left');
        },
        sortMainWidgets: function(ui) {
            return DistantViewEditor.sortWidgets('main');
        },
        sortWidgets: function(position) {
            let widgetNames = [];
            $('.sorting-item-main').each(function() {
                widgetNames.push($(this).attr('data-widget'));
            });
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo App::getContainer()->getUrl()->getHttpDomain(); ?>/admin/pageAssembler/distantViewEditor/sortWidgets',
                'data': {
                    'builtPageId': DistantViewEditor.builtPageId,
                    'position': position,
                    'widgetNames': widgetNames
                },
                'async': true,
                'success': function(response) {
                    ElastiTools.checkResponse(response);
                    DistantViewEditor.loadBase();
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        },
    };

$('document').ready(function() {
    $('.editBuiltPage-editableRoute').off('click')
    $('.editBuiltPage-editableRoute').on('click', function(e) {
        var routeId = $(this).attr('data-routeid');
        var routeName = $(this).attr('data-routename');
        var route = $(this).attr('data-route');
        // console.log('alma!!!!!!' + alma);
        $('#SiteBuilderPackage_editBuiltPage_routeId').val(routeId);
        $('#SiteBuilderPackage_editBuiltPage_routeName').val(routeName);
        $('#SiteBuilderPackage_editBuiltPage_route').val(route);
        EditBuiltPageModal.hideDedicatedRoutes(e);
        EditBuiltPageModal.showRouteNameInput();
    });
//     if (DistantViewEditor.sortableListenerInitialized == false) {
//         $( "#publicWidget-left-sortable").sortable({
//             create: function(event, ui) {
//                 if (event.type != 'sortcreate') {
//                     DistantViewEditor.sortLeftWidgets(ui);
//                 }
//             },
//             stop: function(event, ui) {
//                 DistantViewEditor.sortLeftWidgets(ui);
//             }
//         });
//         $( "#publicWidget-main-sortable").sortable({
//             create: function(event, ui) {
//                 if (event.type != 'sortcreate') {
//                     DistantViewEditor.sortMainWidgets(ui);
//                 }
//             },
//             stop: function(event, ui) {
//                 DistantViewEditor.sortMainWidgets(ui);
//             }
//         });
//     }
//     DistantViewEditor.sortableListenerInitialized = true;
});
</script>