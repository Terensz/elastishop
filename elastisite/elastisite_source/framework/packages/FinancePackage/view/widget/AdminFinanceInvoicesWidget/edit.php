<?php
// dump($entity);
// dump();
?>
<!-- <div id="editInvoice_invoiceId" style="display: none;"><?php echo $entity->getId(); ?></div> -->
<div>
    <div style="float: left; width: 50%;">
        <a href="<?php echo $httpDomain; ?>/admin/finance/downloadInvoice/<?php echo $entity->getId(); ?>"><?php echo trans('download.invoice'); ?></a>
    </div>
    <div class="warning-button" style="width: 50%; margin-left: 50%; text-align: right;">
    <?php if ($entity->getInvoiceType() == $entity::INVOICE_TYPE_REGULAR && !$fullyCredited): ?>
        <div id="editInvoice_createCreditNote_initLinkContainer">
            <a id="editInvoice_createCreditNote_button" href="" onclick="EditInvoice.createCreditNoteInit(event);"><?php echo trans('create.credit.note'); ?></a>
        </div>
    <?php endif; ?>
        <span id="editInvoice_createCreditNote_placeholder">&nbsp;</span>
    </div>
</div>
<div id="editInvoice_createCreditNote_controlPanel" style="display: none;">
    <div class="widgetWrapper-info2">
        <?php echo trans('making.credit.note.warning'); ?>
    </div>
    <div id="editInvoice_createCreditNote_confirmLinkContainer">
        <a href="" onclick="LoadingHandler.start(); EditInvoice.createCreditNote(event);"><?php echo trans('making.credit.note.confirm'); ?></a>
    </div>
    <div id="editInvoice_createCreditNote_createdCreditNoteResult" style="display: none;"></div>
</div>
<?php if ($fullyCredited): ?>
    <div class="widgetWrapper-info2">
        <b><?php echo trans('fully.credited'); ?></b>
    </div>
<?php endif; ?>
<?php if ($entity->getInvoiceType() == $entity::INVOICE_TYPE_REGULAR): ?>
<!-- <div>
    Regular
</div> -->
<?php endif; ?>
<?php if ($entity->getInvoiceType() == $entity::INVOICE_TYPE_CORRECTION): ?>
<div class="widgetWrapper-info">
    <?php echo trans('correction.invoice'); ?>
</div>
<?php endif; ?>

<div style="border: 1px solid #c0c0c0; padding: 10px;">
<?php echo $invoiceView; ?>
</div>

<script>
    var EditInvoice = {
        createCreditNoteInit: function(e) {
            e.preventDefault();
            // let id = $('#editInvoice_invoiceId').html();
            // $('#editInvoice_createCreditNote_button').hide();
            $('#editInvoice_createCreditNote_controlPanel').show();
        },
        createCreditNote: function(e) {
            e.preventDefault();
            // let id = $('#editInvoice_invoiceId').html();
            // $('#editInvoice_createCreditNote_button').hide();
            // $('#editInvoice_createCreditNote_controlPanel').show();
            // console.log('storno');
            var ajaxData = {};
            $.ajax({
                'type' : 'POST',
                'url' : '<?php echo $httpDomain; ?>/admin/finance/createCreditNote/<?php echo $entity->getId(); ?>',
                'data': ajaxData,
                'async': true,
                'success': function(response) {
                    LoadingHandler.stop();
                    console.log(response);
                    $('#editInvoice_createCreditNote_createdCreditNoteResult').html(response.data.message);
                    if (response.data.result == true) {
                        $('#editInvoice_createCreditNote_createdCreditNoteResult').addClass('widgetWrapper-info');
                        $('#editInvoice_createCreditNote_initLinkContainer').html('');
                        $('#editInvoice_createCreditNote_confirmLinkContainer').html('');
                        AdminFinanceInvoicesDataGrid.list(true);
                    } else {
                        $('#editInvoice_createCreditNote_createdCreditNoteResult').addClass('widgetWrapper-info2');
                    }
                    $('#editInvoice_createCreditNote_createdCreditNoteResult').show();
                    // console.log(response);
                    // var params = BannerWidget.getParameters();
                    // $(params.responseSelector).html(response.view);
                },
                'error': function(request, error) {
                    console.log(request);
                    console.log(" Can't do because: " + error);
                },
            });
        }
        // download: function(e) {
        //     e.preventDefault();
        //     console.log('download');
        //     // var params = BannerWidget.getParameters();
        //     var ajaxData = {};
        //     // if (isSubmitted === true) {
        //     //     var form = $('#BannerWidget_form');
        //     //     ajaxData = form.serialize();
        //     // }
        //     $.ajax({
        //         'type' : 'POST',
        //         'url' : '<?php echo $httpDomain; ?>/admin/finance/downloadInvoice',
        //         'data': ajaxData,
        //         'async': true,
        //         'success': function(response) {
        //             console.log(response);
        //             // var params = BannerWidget.getParameters();
        //             // $(params.responseSelector).html(response.view);
        //         },
        //         'error': function(request, error) {
        //             console.log(request);
        //             console.log(" Can't do because: " + error);
        //         },
        //     });
        // }
    };
</script>
