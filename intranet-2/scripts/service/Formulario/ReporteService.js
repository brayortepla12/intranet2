app.service("ReporteService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.postReporte = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/Reporte.php",
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
                url: "/Polivalente/api/Reporte.php",
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
                url: "/Polivalente/api/Reporte.php",
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
                url: "/Polivalente/api/Reporte.php",
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
                url: "/Polivalente/api/Reporte.php"
            });
            return req;
        };
        this.GetAllReportes = function (UsuarioId, Dia, Mes, Year) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: `/Polivalente/api/Reporte.php?UsuarioId_all=${UsuarioId}&Dia_all=${Dia}&Mes_all=${Mes}&Year_all=${Year}`
            });
            return req;
        };
        this.SendEmail = function (ReporteId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/Reporte.php?ReporteId_Send=" + ReporteId
            });
            return req;
        };
        this.AutoFirmar = function (UsuarioId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/Reporte.php?AutoFirmar=" + UsuarioId
            });
            return req;
        };
        this.GetReporteById = function (ReporteId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/Reporte.php?ReporteId=" + ReporteId
            });
            return req;
        };
        this.GetReporteByRecibeId = function (RecibeId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/Reporte.php?RecibeId=" + RecibeId
            });
            return req;
        };
        this.AutoFirmarByRecibeId = function (RecibeId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/Reporte.php?AutoFirmar_RecibeId=" + RecibeId
            });
            return req;
        };
        this.GetReporteByServicioId = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/Reporte.php?SedeId=" + obj.SedeId + "&ServicioId=" + obj.ServicioId + "&Year=" + obj.Year + "&Mes=" + obj.Mes
            });
            return req;
        };
        this.GetReporteByEquipoId = function (EquipoId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/Reporte.php?EquipoId=" + EquipoId
            });
            return req;
        };
        this.GetReporteBySolicitudId = function (SolicitudId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/Reporte.php?SolicitudId=" + SolicitudId
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
                url: "/Polivalente/api/Reporte.php?Year=" + Year + "&Month=" + Month
            });
            return req;
        };
        this.GetReportesBetweenFecha = function (From, To, UsuarioId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/Reporte.php?From=" + From + "&To=" + To + "&UsuarioId=" + UsuarioId
            });
            return req;
        };
        this.GetALLEstadisticas = function (From, To, UsuarioId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/Reporte.php?From2=" + From + "&To2=" + To + "&UsuarioId2=" + UsuarioId
            });
            return req;
        };
        this.GetALLReportesDiariosByUsuarioId = function (UsuarioId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/Reporte.php?UsuarioIdPlantaElectrica=" + UsuarioId
            });
            return req;
        };
    }]);


