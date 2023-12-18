<script>
	var CP = {
		viewStateChange: false,
		load: function() {
			let cpViewState = $('#cp-viewState').html();
			let ajaxData = {};
			if (typeof(cpViewState) !== 'undefined') {
				ajaxData['viewState'] = cpViewState;
			}
			$.ajax({
				'type' : 'POST',
				'url' : '/cp/load',
				'data': ajaxData,
				'async': true,
				'success': function(response) {
					$('#cp-container').html(response.view);
					if (CP.viewStateChange == true) {
						Structure.call();
					}
					CP.viewStateChange = false;
				},
				'error': function(request, error) {
					console.log(request);
					console.log(" Can't do because: " + error);
				}
			});
		}
	};
</script>