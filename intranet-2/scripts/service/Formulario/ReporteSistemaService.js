app.service("ReporteSistemaService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.postReporte = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/ReporteSistema.php",
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
                url: "/Polivalente/api/ReporteSistema.php",
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
        this.putReporte = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/ReporteSistema.php",
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
        this.FirmarReporte = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/ReporteSistema.php",
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
        this.GetReporteByPersonaRecibeId = function (PersonaRecibeId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSistema.php?PersonaRecibeId=" + PersonaRecibeId
            });
            return req;
        };
        this.AutoFirmarByRecibeId = function (RecibeId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSistema.php?AutoFirmar_RecibeId=" + RecibeId
            });
            return req;
        };
        this.GetNReporte = function () {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSistema.php"
            });
            return req;
        };
        this.GetAllReportes = function (UsuarioId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSistema.php?UsuarioId_all=" + UsuarioId
            });
            return req;
        };
        this.GetAllReportes = function (UsuarioId, SedeId, ServicioId, TipoServicio, TipoArticulo) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSistema.php?UsuarioId_all=" + UsuarioId + "&SedeId_all=" + SedeId + "&ServicioId_all=" + ServicioId + "&TipoServicio_all=" + TipoServicio + "&TipoArticulo_all=" + TipoArticulo
            });
            return req;
        };
        this.SendEmail = function (ReporteId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSistema.php?ReporteId_Send=" + ReporteId
            });
            return req;
        };
        this.AutoFirmar = function (UsuarioId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSistema.php?AutoFirmar=" + UsuarioId
            });
            return req;
        };
        this.GetReporteById = function (ReporteId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSistema.php?ReporteId=" + ReporteId
            });
            return req;
        };
        this.GetReporteByServicioId = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSistema.php?SedeId=" + obj.SedeId + "&ServicioId=" + obj.ServicioId + "&Year=" + obj.Year + "&Mes=" + obj.Mes
            });
            return req;
        };
        this.GetReporteByEquipoId = function (EquipoId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSistema.php?EquipoId=" + EquipoId
            });
            return req;
        };
        this.GetReporteBySolicitudId = function (SolicitudId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSistema.php?SolicitudId=" + SolicitudId
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
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSistema.php?Year=" + Year + "&Month=" + Month
            });
            return req;
        };
        this.GetReportesBetweenFecha = function (From, To, UsuarioId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSistema.php?From=" + From + "&To=" + To + "&UsuarioId=" + UsuarioId
            });
            return req;
        };
        this.GetALLEstadisticas = function (From, To, UsuarioId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSistema.php?From2=" + From + "&To2=" + To + "&UsuarioId2=" + UsuarioId
            });
            return req;
        };
        this.GetALLReportesDiariosByUsuarioId = function (UsuarioId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSistema.php?UsuarioIdPlantaElectrica=" + UsuarioId
            });
            return req;
        };
    }]);


