app.service("RondaVerificacionService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.postRondaVerificacion = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/RondaVerificacion.php",
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
        
        this.putRondaVerificacion = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/RondaVerificacion.php",
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
        
        this.deleteRondaVerificacion = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'DELETE',
                url: "/Polivalente/api/RondaVerificacion.php",
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
        
        this.getEstadisticas = (Mes, Year, Estado) => {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/RondaVerificacion.php?Mes_estadisticas=${Mes}&Year_estadisticas=${Year}&Estado_estadisticas=${Estado}`);
            return req;
        };
        
        this.GetHistoricoByPacienteAndMedicamentoId = (IdAfiliado, MedicamentoId) => {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/RondaVerificacion.php?IdAfiliado=${IdAfiliado}&MedicamentoId=${MedicamentoId}&`);
            return req;
        };
        
        this.getRondaVerificacion = function (UsuarioId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/RondaVerificacion.php?UsuarioId=" + UsuarioId);
            return req;
        };
        
        this.GetPreviewRondas = function (RondaVerificacionId, MedicamentoId, Fecha) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/RondaVerificacion.php?RondaVerificacionId_preview=" + RondaVerificacionId + "&MedicamentoId_preview=" + MedicamentoId + "&Fecha_preview=" + Fecha);
            return req;
        };
        
        this.GetConsecutivos = function (RondaVerificacionId, MedicamentoId, Fecha) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/RondaVerificacion.php?RondaVerificacionId_c=" + RondaVerificacionId + "&MedicamentoId_c=" + MedicamentoId + "&Fecha_c=" + Fecha);
            return req;
        };
        
        this.GetExcelRondas = function (RondaVerificacionId, TipoMedicamentoId, Fecha) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/RondaVerificacion.php?RondaVerificacionId_preview=" + RondaVerificacionId + "&TipoMedicamentoId_preview=" + TipoMedicamentoId + "&Fecha_preview=" + Fecha);
            return req;
        };
        
        
        this.getRondaVerificacionById = function (RondaVerificacionId, TipoMedicamento, Sector, Fecha, TipoRonda) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/RondaVerificacion.php?RondaVerificacionId=" + RondaVerificacionId + "&TipoMedicamentoId=" + TipoMedicamento + "&Sector=" + Sector + "&Fecha=" + Fecha + "&TipoRonda=" + TipoRonda);
            return req;
        };
        
        this.GetAll_lite = function (UsuarioId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/RondaVerificacion.php?Lite=" + UsuarioId);
            return req;
        };
        this.GetPreviewProductoInicial = function(RondaVerificacionId, MedicamentoId){
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/RondaVerificacion.php?RondaVerificacionId_preview2=" + RondaVerificacionId + "&MedicamentoId_preview2=" + MedicamentoId);
            return req;
        };
    }]);


