app.service("OrdenRRService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.postOrdenRR = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/OrdenRR.php",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                transformRequest: function (obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
                data: obj
            });
            return req;
        };
        
        this.putOrdenRR = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/OrdenRR.php",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                transformRequest: function (obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
                data: obj
            });
            return req;
        };
        
        this.getOrdenRR = function (UsuarioId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/OrdenRR.php?UsuarioId=" + UsuarioId);
            return req;
        };
        
        this.GetPreviewRondas = function (OrdenRRId, MedicamentoId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/OrdenRR.php?OrdenRRId_preview=" + OrdenRRId + "&MedicamentoId_preview=" + MedicamentoId);
            return req;
        };
        
        this.GetExcelRondas = function (OrdenRRId, TipoMedicamentoId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/OrdenRR.php?OrdenRRId_preview=" + OrdenRRId + "&TipoMedicamentoId_preview=" + TipoMedicamentoId);
            return req;
        };
        
        
        this.getOrdenRRById = function (OrdenRRId, TipoMedicamento, Sector) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/OrdenRR.php?OrdenRRId=" + OrdenRRId + "&TipoMedicamentoId=" + TipoMedicamento + "&Sector=" + Sector);
            return req;
        };
        
        this.GetAll_lite = function (UsuarioId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/OrdenRR.php?Lite=" + UsuarioId);
            return req;
        };
        this.GetPreviewProductoInicial = function(OrdenRRId, MedicamentoId){
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/OrdenRR.php?OrdenRRId_preview2=" + OrdenRRId + "&MedicamentoId_preview2=" + MedicamentoId);
            return req;
        };
        this.GetOrdenByFecha = function(Fecha, TipoMedicamento){
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/OrdenRR.php?Fecha=" + Fecha + "&TipoMedicamento=" + TipoMedicamento);
            return req;
        };
    }]);


