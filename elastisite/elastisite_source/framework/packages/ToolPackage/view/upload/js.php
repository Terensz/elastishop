<script>

var Upload = function (file) {
    this.file = file;
};

Upload.prototype.getType = function() {
    return this.file.type;
};
Upload.prototype.getSize = function() {
    return this.file.size;
};
Upload.prototype.getName = function() {
    return this.file.name;
};
Upload.prototype.doUpload = function (fieldId, url, callbackFunction, dataIndexCallbackParameter, additionalData) {
    if (typeof(callbackFunction) == 'undefined') {
        callbackFunction = null;
    }
    if (typeof(dataIndexCallbackParameter) == 'undefined') {
        dataIndexCallbackParameter = null;
    }
    if (typeof(additionalData) == 'undefined') {
        additionalData = null;
    }
    var that = this;
    var formData = new FormData();
    formData.append(fieldId, this.file, this.getName());
    if (additionalData) {
        for (const [key, value] of Object.entries(additionalData)) {
            // console.log(key, value);
            formData.append(key, value);
        }
    }
    // LoadingHandler.start();
    $.ajax({
        type: "POST",
        url: url,
        async: true,
        // data: {
        //     'alma': 'körte'
        // },
        xhr: function () {
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) {
                myXhr.upload.addEventListener('progress', that.progressHandling, false);
            }
            return myXhr;
        },
        success: function (response) {
            LoadingHandler.stop();
            ElastiTools.checkResponse(response);
            // console.log(typeof(callback));
            if (typeof(callbackFunction) === 'string') {
                // console.log(response.data);
                console.log('upload callbackFunction: ' + callbackFunction);
                if (callbackFunction !== null) {
                    if (dataIndexCallbackParameter === null) {
                        eval(callbackFunction + "('" + JSON.stringify(response.data) + "')");
                    } else {
                        eval(callbackFunction + "('" + response.data[dataIndexCallbackParameter] + "')");
                    }
                } else {
                    // eval(callbackFunction + "()");
                }
            }
        },
        error: function (error, response) {
            ElastiTools.checkResponse(response);
        },
        // async: true,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        timeout: 60000
    });
};

Upload.prototype.progressHandling = function (event) {
    LoadingHandler.start();
    var percent = 0;
    var position = event.loaded || event.position;
    var total = event.total;
    if (event.lengthComputable) {
        percent = Math.ceil(position / total * 100);
    }
    // update progressbars classes so it fits your code
    $(".uploadProgressBar").css("width", +percent + "%");
    $(".uploadStatus").text(percent + "%");
};
</script>
