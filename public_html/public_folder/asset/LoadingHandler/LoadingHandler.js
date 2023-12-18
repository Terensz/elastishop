var LoadingHandler = {
    start: function(target) {
        // console.log('LoadingHandler started');
        if (typeof(target) == 'undefined') {
            target = 'body';
        }
        LoadingHandler.displayVeil(target);
        LoadingHandler.drawSpinner();
    },
    stop: function() {
        // console.log('LoadingHandler stopped');
        LoadingHandler.removeVeil();
    },
    drawSpinner: function() {
        var spinnerContainer = '<div class="spinnerContainer"></div>';
        var spinner = '<div id="ajaxLoadingSpinner" class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>';
        $('#pageVeil').html(spinnerContainer);
        $('.spinnerContainer').html(spinner);
    },
    displayVeil: function(target) {
        var opacity = 0.2;

        // Create veil (delete any existing veils)
        LoadingHandler.removeVeil();
        var veil = '<div id="pageVeil" style="z-index: 999999999; width: 100%; height: 100%; background: black; top: 0; left: 0; position: fixed; opacity: ' + opacity + '; "></div>';

        $('' + target + '').prepend(veil);
        // veil = $('#pageVeil');
        // veil.fadeTo(250,opacity);
  },
  removeVeil: function() {
      $('#pageVeil').remove();
  }
};
