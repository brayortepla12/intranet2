app.filter('autoSuma', function () {
    return function (data, key) {
//        debugger;
        if (angular.isUndefined(data) || angular.isUndefined(key))
            return 0;
        var sum = 0;

        angular.forEach(data, function (v, k) {
            sum = sum + parseInt(v[key]);
        });
        return sum;
    }
});

app.filter('autoSumaTime', function () {
    return function (data, key) {
//        debugger;
        if (angular.isUndefined(data) || angular.isUndefined(key))
            return 0;
        var hour=0;
        var minute=0;
        var second=0;

        angular.forEach(data, function (v, k) {
            var splitTime1 = v[key].split(':');
            hour = hour + parseInt(splitTime1[0]);
            minute = minute + parseInt(splitTime1[1]);
            second = parseInt(splitTime1[2]);
        });
        minute = minute + second / 60;
        hour = hour + (minute / 60);
        minute = minute % 60;
        second = second % 60;
        return `${Math.floor(hour)}:${Math.floor(minute)}:${Math.floor(second)}`;
    }
});
