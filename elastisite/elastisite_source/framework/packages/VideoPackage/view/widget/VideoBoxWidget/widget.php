
<!-- <button type="button" class="btn btn-info">Kérem a videot</button> -->

<div class="lightOpacity" id="videoBox"></div>
<style>
	#videoBox {
		/* position: absolute; */
		display: none;
		position: fixed;
		bottom: 0px;
		right: 0px;
		width: 320px;
		height: 212px;
		/* width: auto;
		height: auto; */
		background-color: #fff;
		z-index: 230;
		box-shadow: 2px 2px 2px #2d3849;
		/* margin-left: 148px;  */
	}

	.videoBoxButton {
		width: 200px;
		height:40px;
		background-color: #3462ae;
		color: #fff;
		box-shadow: 1px 1px 1px #667896;
	}
</style>
<script>
var VideoBox = {
	call: function(obj, evt, fn) {
		$.ajax({
	        'type' : 'POST',
	        'url' : '/videoPlayer/VideoPlayerWidget',
	        'data': {},
	        'async': true,
			'success': function(response) {
				// console.log('alma!!!!');
				ElastiTools.checkResponse(response);
				$('#videoBox').css('display', 'inline');
				$('#videoBox').html(response.view);
	        },
	        'error': function(request, error) {
				ElastiTools.checkResponse(request.responseText);
	            // console.log(request);
	            console.log(" Can't do because: " + error);
	        },
	    });
	},
	minimalize: function() {
		$('#videoBox-player').hide();
		$('#videoBox').css('height', '32px');
		$('#videoBox-min').hide();
		$('#videoBox-expand').show();
	},
	expand: function() {
		$('#videoBox').css('height', '212px');
		$('#videoBox-player').show();
		$('#videoBox-expand').hide();
		$('#videoBox-min').show();
	}
};

$(document).ready(function() {
	// dragElement(document.getElementById("videoBox"));
    VideoBox.call();
});
</script>