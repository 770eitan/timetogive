window.utcToLocalTime = function utcToLocalTime(utcTimeString) {
    var theTime = moment.utc(utcTimeString).toDate(); // moment date object in local time
    var localTime = moment(theTime).format("llll"); //format the moment time object to string

    return localTime;
};

// format time with moment
window.addEventListener("load", function () {
    var collection = $(".format-time");
    collection.each(function () {
        try {
            let dd = $(this).data("time");
            $(this).html(utcToLocalTime(dd));
        } catch (error) {}
    });
});
