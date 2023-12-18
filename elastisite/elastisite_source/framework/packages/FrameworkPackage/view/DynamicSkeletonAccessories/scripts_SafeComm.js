var SafeComm = {
    cache: {},

    // Check if there is an internet connection
    checkInternetConnection: function() {
        return navigator.onLine;
    },

    // Get cached data by key
    getCachedData: function(key) {
        return this.cache[key] ? this.cache[key].data : null;
    },

    // Set cached data with key and URL
    setCachedData: function(key, data, url) {
        this.cache[key] = { data: data, url: url };
    },

    // Fetch data using AJAX and handle caching
    fetchData: function(url, ajaxData, callback) {
        if (!this.checkInternetConnection() && this.getCachedData(url)) {
            var cachedEntry = this.cache[url];
            callback(cachedEntry.data, cachedEntry.url);
        } else {
            this.setCachedData(url, null, null); // Reset data before AJAX call
            // AJAX call or data loading logic here
            // Example AJAX call using jQuery:
            $.ajax({
                url: url,
                data: ajaxData,
                success: function(data) {
                    SafeComm.setCachedData(url, data, url);
                    callback(data, url);
                },
                complete: function(xhr) {
                    if (xhr.status === 200) {
                        // Clear the cache if the response is successful
                        SafeComm.setCachedData(url, null, null);
                    }
                }
            });
        }
    }
};
