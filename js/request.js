// Jquery AJAX request
function request(url, type, data, callback) {
    $.ajax({
        url: url,
        type: type,
        data: data,

        success: function(data) {
            callback(data);
        },
        error: function(data) {
            console.log('Error ' + data);
        }
    });
}