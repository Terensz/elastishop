
<script>
var IdleListener = {
    updatedAt: 0,
    listen: function(){
        var lastUpdatedAt = IdleListener.updatedAt;
        var ms = 500;
        var start = new Date().getTime();
        var end = start;
        while (end < start + ms) {
            console.log(IdleListener.updatedAt);
            if (IdleListener.updatedAt != lastUpdatedAt) {
                console.log('Updated');
                return;
            }
            end = new Date().getTime();
            if ((end - start) == ms) {
                console.log('Eddig vartam!');
            }
        }
    },
    update: function() {
        IdleListener.updatedAt = new Date().getTime();
    }
};

var <?php echo $dataGridId; ?>AjaxTimer = {
    timeoutStarted: false,
    lastButtonPress: null,
    check: function() {
        var now = new Date().getTime();
        var result = false;
        if ((now - <?php echo $dataGridId; ?>AjaxTimer.lastButtonPress) > 600) {
            result = true;
        }
        return result;
    },
    timedAjaxReload: function() {
        var now = new Date().getTime();
        var result = false;
        <?php echo $dataGridId; ?>AjaxTimer.lastButtonPress = now;
        if (<?php echo $dataGridId; ?>AjaxTimer.timeoutStarted === false) {
            <?php echo $dataGridId; ?>AjaxTimer.timeoutStarted = true;
            setTimeout(function () {
                result = <?php echo $dataGridId; ?>AjaxTimer.check();
                <?php echo $dataGridId; ?>AjaxTimer.timeoutStarted = false;
                if (result === false) {
                    return <?php echo $dataGridId; ?>AjaxTimer.timedAjaxReload();
                } else {
                    <?php echo $dataGridId; ?>.list(true);
                }
            }, 1200);
        }
        return result;
    }
};

var <?php echo $dataGridId; ?> = {
    orderByProp: null,
    orderByDirection: null,
    page: '1',
    filteredIds: [],
    displayedIds: [],
    orderBy: function(orderByProp, orderByDirection) {
        // console.log('orderByProp: ' + orderByProp + ', orderByDirection: ' + orderByDirection);
        <?php echo $dataGridId; ?>.orderByProp = orderByProp;
        <?php echo $dataGridId; ?>.orderByDirection = orderByDirection;

        console.log('OrderBy!');
        console.log('<?php echo $dataGridId; ?>.orderByProp:' + <?php echo $dataGridId; ?>.orderByProp);
        console.log('<?php echo $dataGridId; ?>.orderByDirection:' + <?php echo $dataGridId; ?>.orderByDirection);
        <?php echo $dataGridId; ?>.list(true);
    },
    setPage: function(event, page) {
        if (event) {
            event.preventDefault();
        }
        console.log('<?php echo $dataGridId; ?>.setPage(event, ' + page + ')');
        <?php echo $dataGridId; ?>.page = page;
        <?php echo $dataGridId; ?>.list(true);
        console.log('page: '+ page);
    },
    list: function(isSubmitted) {
        // console.log('<?php echo $dataGridId; ?>.list()');
        LoadingHandler.start('#dataGrid-<?php echo $dataGridId; ?>');
        var ajaxData = {};
        if (isSubmitted === true) {
            var form = $('#<?php echo $dataGridId; ?>_form');
            var formData = form.serialize();
            // console.log('form: ', form);
            // console.log('formData: ', formData);
            // console.log('listActionUrl: <?php echo $listActionUrl; ?>');
            var additionalData = {
                'currentPage': <?php echo $dataGridId; ?>.page,
                'preload': false,
                'orderByProp': <?php echo $dataGridId; ?>.orderByProp,
                'orderByDirection': <?php echo $dataGridId; ?>.orderByDirection
            };
            ajaxData = formData + '&' + $.param(additionalData);
        }
        var focusedElement = $(':focus');
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $listActionUrl; ?>',
            'data': ajaxData,
            'async': true,
            'success': function(response) {
                // console.log(response);
                ElastiTools.checkResponse(response);
                // var params = ESConditionsDocumentReaderWidget.getParameters();
                // console.log(response);
                $('#dataGrid-<?php echo $dataGridId; ?>').html(response.view);
                LoadingHandler.stop();

                if (focusedElement) {
                    let focusedElementId = focusedElement.attr('id');
                    console.log('focusedElementId: ', focusedElementId);
                    if (focusedElementId && focusedElementId != '') {
                        var searchInput = $('#' + focusedElement.attr('id'));
                        if (typeof(searchInput.attr('id')) != 'undefined') {
                            if (typeof(searchInput) == 'object' && typeof(searchInput[0]) != 'undefined' && searchInput[0] != null && typeof(searchInput[0].setSelectionRange) == 'function') {
                                var strLength = searchInput.val().length * 2;
                                searchInput.focus();

                                searchInput[0].setSelectionRange(strLength, strLength);
                            }
                        }
                    }
                }
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                LoadingHandler.stop();
            },
        });
    },
    new: function(event) {
        if (event) {
            event.preventDefault();
        }
        <?php echo $dataGridId; ?>.edit(null);
    },
    deleteRequest: function(event, id) {
        if (event) {
            event.preventDefault();
        }
        // console.log('delete: ' + id);
        $('#confirmModalConfirm').attr('onClick', "<?php echo $dataGridId; ?>.deleteConfirmed(" + id + ");");
        $('#confirmModalBody').html('<?php echo trans('are.you.sure'); ?>');
        $('#confirmModal').modal('show');
    },
    deleteConfirmed: function(id) {
        LoadingHandler.start('#dataGrid-<?php echo $dataGridId; ?>');
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $deleteActionUrl; ?>',
            'data': {'id': id},
            'async': true,
            'success': function(response) {
                // console.log(response);
                // var params = ESConditionsDocumentReaderWidget.getParameters();
                $('#confirmModal').modal('hide');
                // console.log('DataGridId: <?php echo $dataGridId; ?>');
                <?php echo $dataGridId; ?>.list(true);
                <?php echo $javaScriptOnDeleteConfirmed; ?>
                LoadingHandler.stop();
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
                LoadingHandler.stop();
            },
        });
    },
    edit: function(event, id) {
        if (event) {
            event.preventDefault();
        }
        $('.grid-body-cell').removeClass('openedItem');
        $('.<?php echo $dataGridId; ?>-' + id).addClass('openedItem');
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $editActionUrl; ?>',
            'data': {'id': id },
            'async': true,
            'success': function(response) {
                // console.log('tatagrid: <?php echo $dataGridId; ?>');
                ElastiTools.checkResponse(response);
                ElastiTools.fillModal(response, null);
                // console.log('response.data', response.data);
                if (response.hasOwnProperty('data')) {
                    // FormValidator.displayErrors('#' + params.formName, response.data.messages);
                    if (response.data.formIsValid === true) {
                        $('#editorModal').modal('hide');
                        <?php echo $dataGridId; ?>.list(true);
                    } else {
                        $('#editorModal').modal('show');
                    }
                }
            },
            'error': function(request) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
};

$(document).ready(function() {
    $('#editorModalLabel').html('');
    $('body').prop('onchange', '.multiselect-input').off('change');
    $('body').on('change', '.multiselect-input', function() {
        <?php echo $dataGridId; ?>.list(true);
    });

    // $('body').off('click', '.dataGrid-text');
    // $('body').on('click', '.dataGrid-text', function(e) {
    //     let id = $(this).attr('id');
    //     console.log('id: ' + id);
    //     <?php echo $dataGridId; ?>.lastInput = id;
    // });

    $('body').off('keyup', '.dataGrid-text');
    $('body').on('keyup', '.dataGrid-text', function(e) {
        if (e.which == 13) {
            // e.stopPropagation();
            <?php echo $dataGridId; ?>.list(true);
        }
        <?php echo $dataGridId; ?>AjaxTimer.timedAjaxReload();
    });

    // $(".dataGrid-text").show();
    // // some DOM manipulation/ajax here
    // window.setTimeout(function () {
    //     $(".dataGrid-text").hide();
    // }, 1);

    // $('body').on('change', '.alma', function() {
    //     <?php echo $dataGridId; ?>.list(true);
    // });

    // $('.dataGrid-text').on('input', function() {
    //     IdleListener.update();
    //     IdleListener.listen();
    //     console.log('update');
    // });
});
</script>