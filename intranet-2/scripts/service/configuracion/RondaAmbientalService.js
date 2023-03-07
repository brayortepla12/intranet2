app.service("RondaAmbientalService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    this.PostRondaAmbiental = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'POST',
                    url: "/Polivalente/api/RondaAmbiental.php",
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
    this.PutRondaAmbiental = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'PUT',
                    url: "/Polivalente/api/RondaAmbiental.php",
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
    this.getAllFormularios = function () {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/RondaAmbiental.php");
        return req;
    };
    
    this.getRondasAmbientalesByServicioId = function (ServicioId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/RondaAmbiental.php?ServicioId=" + ServicioId);
        return req;
    };
    
    this.getFormularioByServicioId = function (ServicioId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/RondaAmbiental.php?ServicioId_form=" + ServicioId);
        return req;
    };
}]);

