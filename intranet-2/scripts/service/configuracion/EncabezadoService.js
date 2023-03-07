app.service("EncabezadoService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    this.PostEncabezado = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'POST',
                    url: "/Polivalente/api/Encabezado.php",
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
    this.PutEncabezado = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'PUT',
                    url: "/Polivalente/api/Encabezado.php",
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
    this.getEncabezado = function () {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Encabezado.php");
        return req;
    };
}]);

