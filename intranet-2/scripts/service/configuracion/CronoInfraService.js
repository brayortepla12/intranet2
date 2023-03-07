app.service("CronoInfraService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    this.PostCronogramaServicio = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'POST',
                    url: "/Polivalente/api/CI.php",
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
    this.PutCronogramaServicio = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'PUT',
                    url: "/Polivalente/api/CI.php",
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
    this.GetAllByServicio = function (Year, ServicioId) {
        cfpLoadingBar.start();
        var req = $http.get(`/Polivalente/api/CI.php?Vigencia_ci=${Year}&ServicioId=${ServicioId}`);
        return req;
    };
    this.getAllCronogramaServicioByUserId = function (UserId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/CI.php?UserId=" + UserId);
        return req;
    };
}]);

