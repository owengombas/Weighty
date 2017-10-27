function request(url, type, data, callback) {
    $.ajax({
        url: url,
        type: type,
        data: data,

        success: (data) => {
            callback(data);
        },
        error: (data) => {
            console.log('Error ' + data);
        }
    });
}