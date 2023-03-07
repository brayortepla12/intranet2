app.service("CunaService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    this.PostCuna = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'POST',
                    url: "/Polivalente/api/Cuna.php",
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
    this.PutCuna = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'PUT',
                    url: "/Polivalente/api/Cuna.php",
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
    this.getAllCuna = function () {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Cuna.php");
        return req;
    };
    this.getAllCunaKristalos = function (UserId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/CamaKristalos.php?UserId=" + UserId);
        return req;
    };
    this.getAllCunaByAdmision = function (Admision) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/CamaKristalos.php?NoAdmision=" + Admision);
        return req;
    };
    this.GetCunaByToken = function (Token) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Cuna.php?Token=" + Token);
        return req;
    };
    this.getAllCunaByUserId = function (UserId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Cuna.php?UserId=" + UserId);
        return req;
    };
}]);

