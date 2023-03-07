app.service("ModuloService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    this.PostModulo = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'POST',
                    url: "/Polivalente/api/Modulo.php",
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
    this.PutModulo = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'PUT',
                    url: "/Polivalente/api/Modulo.php",
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
    this.getAllModulo = function () {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Modulo.php");
        return req;
    };
    this.getAllModuloByUserId = function (UserId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Modulo.php?UserId=" + UserId);
        return req;
    };
    this.getAllModuloByUsuarioLiderId = function (UserId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Modulo.php?LiderUsuarioId=" + UserId);
        return req;
    };
    
}]);

