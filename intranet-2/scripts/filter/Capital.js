app.filter('capital', function () {
    return function (input, scope) {
        if (input != undefined) {
            if (input != null)
                input = input.toLowerCase();
            return input.substring(0, 1).toUpperCase() + input.substring(1);
        }
    }
});

