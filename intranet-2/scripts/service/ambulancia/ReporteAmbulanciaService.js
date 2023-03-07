app.service("ReporteAmbulanciaService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.postReporte = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/ReporteAmbulancia.php",
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
        this.PutReporte = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/ReporteAmbulancia.php",
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
        this.DeleteReporte = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'DELETE',
                url: "/Polivalente/api/ReporteAmbulancia.php",
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
        this.GetNReporte = function () {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteAmbulancia.php"
            });
            return req;
        };
        this.GetAllReportes = function (SedeId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteAmbulancia.php?SedeId_all=" + SedeId
            });
            return req;
        };
        this.GetReporteById = function (ReporteId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteAmbulancia.php?ReporteId=" + ReporteId
            });
            return req;
        };
        this.GetReporteByServicioId = function (ReporteId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteAmbulancia.php?ServicioId=" + ReporteId
            });
            return req;
        };
        this.GetReporteByEquipoId = function (EquipoId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteAmbulancia.php?EquipoId=" + EquipoId
            });
            return req;
        };
        
        this.GetCronogramaByEquipoId = function (EquipoId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteAmbulancia.php?EquipoId_crono=" + EquipoId
            });
            return req;
        };
        
        
        this.GetReporteBySolicitudId = function (SolicitudId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteAmbulancia.php?SolicitudId=" + SolicitudId
            });
            return req;
        };
        this.PostReporteEscaneado = function (file, obj) {
            cfpLoadingBar.start();
            var fd = new FormData();
            fd.append('file', file);
            fd.append('Objeto', JSON.stringify(obj));
            console.log(obj)
            var req = $http.post("/Polivalente/api/UploadFile.php", fd, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined, 'Process-Data': false}
            });
            return req;
        };
        this.GetEstadisticas = function (Year, Month) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteAmbulancia.php?Year=" + Year + "&Month=" + Month
            });
            return req;
        };
        this.GetReportesBetweenFecha = function (From, To, UsuarioId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteAmbulancia.php?From=" + From + "&To=" + To + "&UsuarioId=" + UsuarioId
            });
            return req;
        };
        this.GetALLEstadisticas = function (From, To, UsuarioId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteAmbulancia.php?From2=" + From + "&To2=" + To + "&UsuarioId2=" + UsuarioId
            });
            return req;
        };
        this.getCronograma = function () {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteAmbulancia.php?Cronograma=True"
            });
            return req;
        };
    }]);




