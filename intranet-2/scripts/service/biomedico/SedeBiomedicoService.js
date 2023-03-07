app.service("SedeBiomedicoService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    this.PostSede = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'POST',
                    url: "/Biomedico/api/Sede.php",
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
                    url: "/Biomedico/api/Sede.php",
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
        var req = $http.get("/Biomedico/api/Sede.php");
        return req;
    };
    this.getAllSedeByUserId = function (UserId) {
        cfpLoadingBar.start();
        var req = $http.get("/Biomedico/api/Sede.php?UserId=" + UserId);
        return req;
    };
}]);

