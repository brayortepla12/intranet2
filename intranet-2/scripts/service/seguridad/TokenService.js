app.service("TokenService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    var base = "/Polivalente/api/Token.php";
    this.isValidToken= function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'POST',
                    url: base,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    transformRequest: function(obj) {
                        var str = [];
                        for(var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                        return str.join("&");
                    },
                    data: obj
                  });
        return req;
    };
}]);
