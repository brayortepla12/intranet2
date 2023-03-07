app.service("ServicioService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    this.PostServicio = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'POST',
                    url: "/Polivalente/api/Servicio.php",
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
                    url: "/Polivalente/api/Servicio.php",
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
        var req = $http.get("/Polivalente/api/Servicio.php");
        return req;
    };
    this.getServicioBySede = function (SedeId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Servicio.php?SedeId=" + SedeId);
        return req;
    };
    this.getServicioBySedeWithTA = function (SedeId, UsuarioId, TA) {
        cfpLoadingBar.start();
        var req = $http.get(`/Polivalente/api/Servicio.php?SedeId_ta=${SedeId}&UsuarioId_ta=${UsuarioId}&TA=${TA}`);
        return req;
    };
    this.getServicioBySedeAndUserId = function (SedeId, UserId) {
        cfpLoadingBar.start();
        var req = $http.get(`/Polivalente/api/Servicio.php?SedeId_2=${SedeId}&UserId_2=${UserId}`);
        return req;
    };
    this.getServicioByUserId= function (UserId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Servicio.php?UserId=" + UserId);
        return req;
    };
    this.getServicioByUserIdRepuesto = function (UserId) {
      cfpLoadingBar.start()
      var req = $http.get('/Polivalente/api/Servicio.php?UserIdRepuesto=' + UserId)
      return req
    }
    this.getAllServicioByUsuarioLiderId= function (UserId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Servicio.php?LiderUsuarioId=" + UserId);
        return req;
    };
    
    this.getServicioByFormatoId= function (FormatoId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Servicio.php?FormatoId=" + FormatoId);
        return req;
    };
}]);

