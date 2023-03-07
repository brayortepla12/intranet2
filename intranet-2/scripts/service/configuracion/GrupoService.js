app.service("GrupoService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    this.PostGrupo = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'POST',
                    url: "/Polivalente/api/Grupo.php",
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
    this.PutGrupo = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'PUT',
                    url: "/Polivalente/api/Grupo.php",
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
    this.getAllGrupo = function () {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Grupo.php");
        return req;
    };
    this.getAllGrupoByUserId = function (UserId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Grupo.php?UserId=" + UserId);
        return req;
    };
}]);

