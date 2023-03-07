app.service("PermisoService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    this.PostPermiso = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'POST',
                    url: "/Polivalente/api/Permiso.php",
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
    this.PutPermiso = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'PUT',
                    url: "/Polivalente/api/Permiso.php",
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
    this.getAllPermiso = function () {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Permiso.php");
        return req;
    };
    this.getAllPermisoByUserId = function (UserId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Permiso.php?UserId=" + UserId);
        return req;
    };
    
    this.getAllPermisoByUsuarioLiderId = function (UserId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Permiso.php?LiderUsuarioId=" + UserId);
        return req;
    };
    
}]);

