<div class="tagFrame-fullWidth-auto mb-2" style="display: inline; height: auto;">
    <div class="tag-light" data-route="[routeName]" style="cursor: pointer; background-color: #a9b7dd;">
        <div class="form-group">
            <div class="input-group">
                <input name="[pageToolId]_search" id="[pageToolId]_search" type="text" class="inputField form-control" value="" aria-describedby="" placeholder="<?php echo trans('search'); ?>">
            </div>
            <!-- <div class="validationMessage error" id="[pageToolId]-errorMessage" style="padding-top:4px;"></div> -->
        </div>
    </div>
</div>

<div style="height: 14px;">
</div>

<script>
var [pageToolId] = {
    search: function() {
        console.log('[pageToolId].search');
        let inputs = $('.[pageToolId]_input');
        for (let i = 0; i < inputs.length; i++) {
            let input = inputs[i];
            let id = $(input).attr('id');
            let search = $('#PageToolView_customPageBasic_search').val();
            search = search.toLowerCase();
            let routeTitle = $('#' + id + '_routeTitle').html();
            routeTitle = routeTitle.toLowerCase();
            let routeParamChains = $('#' + id + '_routeParamChains').html();
            routeParamChains = routeParamChains.toLowerCase();

            // console.log(input);
            // console.log('search: ' + search);
            // console.log('routeTitle: ' + routeTitle);
            // console.log('routeParamChains: ' + routeParamChains);
            // let match = '/^.*' + search + '$/';

            let match = search;

            if (routeTitle.includes(match) || routeParamChains.includes(match) || search == '') {
                $('#' + id).show();
            } else {
                $('#' + id).hide();
            }
        }
    }
};
$('document').ready(function() {
    $('#[pageToolId]_search').on('keyup', function() {
        [pageToolId].search();
    });
});
</script>