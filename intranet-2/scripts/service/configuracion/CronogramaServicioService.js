app.service("CronogramaServicioService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    this.PostCronogramaServicio = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'POST',
                    url: "/Polivalente/api/CronogramaServicio.php",
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
                    url: "/Polivalente/api/CronogramaServicio.php",
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
    this.getAllCronogramaServicio = function () {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/CronogramaServicio.php");
        return req;
    };
    this.getAllCronogramaServicioByUserId = function (UserId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/CronogramaServicio.php?UserId=" + UserId);
        return req;
    };
}]);

