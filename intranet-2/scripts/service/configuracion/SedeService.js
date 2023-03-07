app.service("SedeService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    this.PostSede = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'POST',
                    url: "/Polivalente/api/Sede.php",
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
    this.PutSede = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'PUT',
                    url: "/Polivalente/api/Sede.php",
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
    this.getAllSede = function () {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Sede.php");
        return req;
    };
    this.getAllSedeByUsuarioLiderId = function (UserId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Sede.php?LiderUsuarioId=" + UserId);
        return req;
    };
    this.getAllSedeByUserId = function (UserId) {
      cfpLoadingBar.start()
      var req = $http.get('/Polivalente/api/Sede.php?UserId=' + UserId)
      return req
    }
    this.getAllSedeByUserIdRepuesto = function (UserId) {
      cfpLoadingBar.start()
      var req = $http.get('/Polivalente/api/Sede.php?UserIdRepuesto=' + UserId)
      return req
    }
    this.getAllSedeByUserId_TA = function (UserId, TA) {
        cfpLoadingBar.start();
        var req = $http.get(`/Polivalente/api/Sede.php?UserId_ta=${UserId}&TA=${TA}`);
        return req;
    };
}]);

