app.service("LoginService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    var base = "/Polivalente/api/Login.php";
    this.Login= function (obj) {
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
