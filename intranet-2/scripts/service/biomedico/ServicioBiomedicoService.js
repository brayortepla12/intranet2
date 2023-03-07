app.service("ServicioBiomedicoService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    this.PostServicio = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'POST',
                    url: "/Biomedico/api/Servicio.php",
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
    this.PutServicio = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'PUT',
                    url: "/Biomedico/api/Servicio.php",
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
    this.getAllServicio = function () {
        cfpLoadingBar.start();
        var req = $http.get("/Biomedico/api/Servicio.php");
        return req;
    };
    this.getServicioBySede = function (SedeId) {
        cfpLoadingBar.start();
        var req = $http.get("/Biomedico/api/Servicio.php?SedeId=" + SedeId);
        return req;
    };
    this.getServicioByUserId= function (UserId) {
        cfpLoadingBar.start();
        var req = $http.get("/Biomedico/api/Servicio.php?UserId=" + UserId);
        return req;
    };
    
    this.getServicioWithExternoByUserId= function (UserId) {
        cfpLoadingBar.start();
        var req = $http.get("/Biomedico/api/Servicio.php?UserIdExt=" + UserId);
        return req;
    };
    
    
}]);

