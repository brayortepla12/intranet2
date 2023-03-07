app.service("ArticuloService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    this.PostArticulo = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'POST',
                    url: "/Polivalente/api/Articulo.php",
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
    this.PutArticulo = function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'PUT',
                    url: "/Polivalente/api/Articulo.php",
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
    this.getAllArticulo = function (Tipo) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Articulo.php?Tipo=" + Tipo);
        return req;
    };
    this.getArticuloByGrupo = function (GrupoId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Articulo.php?GrupoId=" + GrupoId);
        return req;
    };
    this.getArticuloByUserId= function (UserId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Articulo.php?UserId=" + UserId);
        return req;
    };
    this.getArticuloByFormatoId= function (FormatoId) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Articulo.php?FormatoId=" + FormatoId);
        return req;
    };
    
    this.getArticulosByPlantilla= function (ServicioId, UserId, Tipo) {
        cfpLoadingBar.start();
        var req = $http.get("/Polivalente/api/Articulo.php?UserId_p=" + UserId + "&ServicioId_p=" + ServicioId + "&Tipo_p=" + Tipo);
        return req;
    };
}]);

