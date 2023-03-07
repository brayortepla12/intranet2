app.service("RondaSistemaServicioService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    this.PostRondaSistemaServicio = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'POST',
                    url: "/Polivalente/api/RondaSistemaServicio.php",
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
    this.PutRondaSistemaServicio = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'PUT',
                    url: "/Polivalente/api/RondaSistemaServicio.php",
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
    this.getAllRondaSistemaServicio = function () {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/RondaSistemaServicio.php");
        return req;
    };
    this.getRondaSistemaServicioBySede = function (SedeId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/RondaSistemaServicio.php?SedeId=" + SedeId);
        return req;
    };
    this.getRondaSistemaServicioByRondaId= function (RondaId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/RondaSistemaServicio.php?RondaId=" + RondaId);
        return req;
    };
    
    this.getRondaSistemaServicioByUsuarioId= function (UsuarioId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/RondaSistemaServicio.php?UsuarioId=" + UsuarioId);
        return req;
    };
    
    this.getRondaSistemaServicioByUsuarioId_Ronda= function (UsuarioId, RondaId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/RondaSistemaServicio.php?UsuarioId2=" + UsuarioId + "&RondaId2=" + RondaId);
        return req;
    };
    
    this.getRondaSistemaServicioByUsuarioId_RondaFECHA= function (UsuarioId, RondaId, Fecha) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/RondaSistemaServicio.php?UsuarioId3=" + UsuarioId + "&RondaId3=" + RondaId + "&Fecha2=" + Fecha);
        return req;
    };
    
    this.getRondaSistemaServicioByFormatoId= function (FormatoId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/RondaSistemaServicio.php?FormatoId=" + FormatoId);
        return req;
    };
}]);

