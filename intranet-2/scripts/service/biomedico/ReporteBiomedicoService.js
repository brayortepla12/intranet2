app.service("ReporteBiomedicoService", ["$http", function ($http) {
        this.postReporte = function (obj) {
            var req = $http({
                method: 'POST',
                url: "/Biomedico/api/Reporte.php",
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
            var req = $http({
                method: 'PUT',
                url: "/Biomedico/api/Reporte.php",
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
            var req = $http({
                method: 'PUT',
                url: "/Biomedico/api/Reporte.php",
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
            var req = $http({
                method: 'DELETE',
                url: "/Biomedico/api/Reporte.php",
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
            var req = $http({
                method: 'GET',
                url: "/Biomedico/api/Reporte.php"
            });
            return req;
        };
        this.GetAllReportes = function (UsuarioId) {
            var req = $http({
                method: 'GET',
                url: "/Biomedico/api/Reporte.php?UsuarioId_all=" + UsuarioId
            });
            return req;
        };
        this.GetAllReportesBySS = function (SedeId, ServicioId) {
            var req = $http({
                method: 'GET',
                url: "/Biomedico/api/Reporte.php?SedeId_all=" + SedeId + "&ServicioId_all=" + ServicioId
            });
            return req;
        };
        
        this.SendEmail = function (ReporteId) {
            var req = $http({
                method: 'GET',
                url: "/Biomedico/api/Reporte.php?ReporteId_Send=" + ReporteId
            });
            return req;
        };
        
        this.SendEmailPendientesByServicio = function (ServicioId) {
            var req = $http({
                method: 'GET',
                url: "/Biomedico/api/Reporte.php?ReportesPendientes_servicio=" + ServicioId
            });
            return req;
        };
        this.AutoFirmar = function (UsuarioId) {
            var req = $http({
                method: 'GET',
                url: "/Biomedico/api/Reporte.php?AutoFirmar=" + UsuarioId
            });
            return req;
        };
        this.AutoFirmarByEmail = function (Email) {
            var req = $http({
                method: 'GET',
                url: "/Biomedico/api/Reporte.php?AutoFirmar_Email=" + Email
            });
            return req;
        };
        this.GetReporteById = function (ReporteId) {
            var req = $http({
                method: 'GET',
                url: "/Biomedico/api/Reporte.php?ReporteId=" + ReporteId
            });
            return req;
        };
        this.GetReporteByEmail = function (Email) {
            var req = $http({
                method: 'GET',
                url: "/Biomedico/api/Reporte.php?Email=" + Email
            });
            return req;
        };
        this.GetReporteByServicioId = function (ReporteId) {
            var req = $http({
                method: 'GET',
                url: "/Biomedico/api/Reporte.php?ServicioId=" + ReporteId
            });
            return req;
        };
        this.GetReporteByEquipoId = function (EquipoId) {
            var req = $http({
                method: 'GET',
                url: "/Biomedico/api/Reporte.php?EquipoId=" + EquipoId
            });
            return req;
        };
        this.GetReporteBySolicitudId = function (SolicitudId) {
            var req = $http({
                method: 'GET',
                url: "/Biomedico/api/Reporte.php?SolicitudId=" + SolicitudId
            });
            return req;
        };
        this.PostReporteEscaneado = function (file, obj) {
            var fd = new FormData();
            fd.append('file', file);
            fd.append('Objeto', JSON.stringify(obj));
            console.log(obj)
            var req = $http.post("/Biomedico/api/UploadFile.php", fd, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined, 'Process-Data': false}
            });
            return req;
        };
        this.GetEstadisticas = function (Year, Month) {
            var req = $http({
                method: 'GET',
                url: "/Biomedico/api/Reporte.php?Year=" + Year + "&Month=" + Month
            });
            return req;
        };
        this.GetReportesBetweenFecha = function (From, To, UsuarioId) {
            var req = $http({
                method: 'GET',
                url: "/Biomedico/api/Reporte.php?From=" + From + "&To=" + To + "&UsuarioId=" + UsuarioId
            });
            return req;
        };
        this.GetALLEstadisticas = function (From, To, UsuarioId) {
            var req = $http({
                method: 'GET',
                url: "/Biomedico/api/Reporte.php?From2=" + From + "&To2=" + To + "&UsuarioId2=" + UsuarioId
            });
            return req;
        };
    }]);


