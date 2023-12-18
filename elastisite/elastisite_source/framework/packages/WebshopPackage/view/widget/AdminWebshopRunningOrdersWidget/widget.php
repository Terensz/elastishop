<div class="card">
    <div class="card-footer"><?php echo trans('running.orders.info', [['from' => '[displayedRunningOrders]', 'to' => $displayedRunningOrders]]); ?></div>
</div>

<div id="WebshopPackage-runningOrders-list"></div>

<script>
    var RunningOrders = {
        orbitUrl: '<?php echo $httpDomain; ?>/admin/webshop/runningOrders',
        refresherEvent: null,
        runningShipmentIds: null,
        getOrderedShipmentIds: function() {
            var orderedShipmentIds = [];
            $.ajax({
                'type' : 'POST',
                'url' : '/admin/webshop/getOrderedShipmentIds',
                'data': {},
                'async': false,
                'success': function(response) {
                    orderedShipmentIds = response.data.orderedShipmentIds;
                    console.log('orderedShipmentIds:');
                    console.log(orderedShipmentIds);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
            return orderedShipmentIds;
        },
        // refreshList: function(prevShipmentId, lastShipmentId) {
        //     $.ajax({
        //         'type' : 'POST',
        //         'url' : '<?php echo $httpDomain; ?>/admin/webshop/runningOrders/getNewListElementIds',
        //         'data': {
        //             'prevShipmentId': prevShipmentId
        //             // 'lastShipmentId': lastShipmentId
        //         },
        //         'async': false,
        //         'success': function(response) {
        //             console.log(response);
        //             var newListElementIds = response.data.newListElementIds;
        //             for (let i = 0; i < newListElementIds.length; i++) {

        //             }
        //             // lastShipmentId = response.data.lastShipmentId;
        //         },
        //         'error': function(request, error) {
        //             console.log(request);
        //             console.log(" Can't do because: " + error);
        //         },
        //     });
        //     // return lastShipmentId;
        // },
        refreshList: function() {
            console.log('RunningOrders.refreshList');
            $.ajax({
                'type' : 'POST',
                'url' : '/admin/webshop/runningOrders/getListView',
                'data': {
                    // 'prevShipmentId': prevShipmentId
                    // 'lastShipmentId': lastShipmentId
                },
                'async': false,
                'success': function(response) {
                    // console.log(response);
                    console.log('refreshList');
                    console.log(response);
                    // console.log(orderedShipmentIds);
                    $('#WebshopPackage-runningOrders-list').html(response.view);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
            // return lastShipmentId;
        },
        detectDisplayedOrderChanges: function() {
            // console.log('RunningOrders.checkNewOrders');
            /**
             * Visiting other page will stop that interval
            */
            if (RunningOrders.orbitUrl != window.location.href) {
                clearInterval(RunningOrders.refresherEvent);
                RunningOrders.refresherEvent = null;
            } else {
                var orderedShipmentIds = RunningOrders.getOrderedShipmentIds();
                // console.log(RunningOrders.orderedShipmentIds);
                // console.log(orderedShipmentIds);
                // console.log('lastShipmentId', lastShipmentId);
                // console.log('RunningOrders.lastShipmentId', RunningOrders.lastShipmentId);
                if (RunningOrders.orderedShipmentIds != orderedShipmentIds) {
                    // var prevShipmentId = RunningOrders.lastShipmentId;
                    // RunningOrders.lastShipmentId = lastShipmentId;
                    RunningOrders.refreshList();
                    RunningOrders.orderedShipmentIds = orderedShipmentIds;
                    console.log('RunningOrders.findUndisplayedOrders - refresh');
                }
            }
        },
        edit: function(e, id) {
            e.preventDefault();
            $('.grid-body-cell').removeClass('openedItem');
            $('.AdminWebshopShipmentsGrid-' + id).addClass('openedItem');
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $httpDomain; ?>/admin/webshop/shipment/edit',
                'data': {'id': id },
                'async': true,
                'success': function(response) {
                    // console.log('tatagrid: AdminWebshopShipmentsGrid');
                    ElastiTools.checkResponse(response);
                    ElastiTools.fillModal(response, null);
                    if (response.hasOwnProperty('data')) {
                        // FormValidator.displayErrors('#' + params.formName, response.data.messages);
                        if (response.data.formIsValid === true) {
                            $('#editorModal').modal('hide');
                            AdminWebshopShipmentsGrid.list(true);
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

    $('document').ready(function() {
        clearInterval(RunningOrders.refresherEvent);
        RunningOrders.refresherEvent = null;
        RunningOrders.detectDisplayedOrderChanges();
        RunningOrders.refresherEvent = setInterval(function() {
            RunningOrders.detectDisplayedOrderChanges();
        }, 3000);
    });
</script>