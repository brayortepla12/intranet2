app.service("ReporteSSTService", ["$http", function ($http) {
        this.postReporte = function (obj) {
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/ReporteSST.php",
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
                url: "/Polivalente/api/ReporteSST.php",
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
                url: "/Polivalente/api/ReporteSST.php",
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
                url: "/Polivalente/api/ReporteSST.php",
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
        this.GetReporteByEmail = function (Email) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSST.php?EmailSolicitante=" + Email
            });
            return req;
        };
        this.AutoFirmarByEmail = function (Email) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSST.php?AutoFirmar_Email=" + Email
            });
            return req;
        };
        this.GetNReporte = function () {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSST.php"
            });
            return req;
        };
        this.GetAllReportes = function (UsuarioId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSST.php?UsuarioId_all=" + UsuarioId
            });
            return req;
        };
        this.SendEmail = function (ReporteId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSST.php?ReporteId_Send=" + ReporteId
            });
            return req;
        };
        this.AutoFirmar = function (UsuarioId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSST.php?AutoFirmar=" + UsuarioId
            });
            return req;
        };
        this.GetReporteById = function (ReporteId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSST.php?ReporteId=" + ReporteId
            });
            return req;
        };
        this.GetReporteByServicioId = function (obj) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSST.php?SedeId=" + obj.SedeId + "&ServicioId=" + obj.ServicioId + "&Year=" + obj.Year + "&Mes=" + obj.Mes
            });
            return req;
        };
        this.GetReporteByEquipoId = function (EquipoId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSST.php?EquipoId=" + EquipoId
            });
            return req;
        };
        this.GetReporteBySolicitudId = function (SolicitudId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSST.php?SolicitudId=" + SolicitudId
            });
            return req;
        };
        this.PostReporteEscaneado = function (file, obj) {
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
                url: "/Polivalente/api/ReporteSST.php?Year=" + Year + "&Month=" + Month
            });
            return req;
        };
        this.GetReportesBetweenFecha = function (From, To, UsuarioId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSST.php?From=" + From + "&To=" + To + "&UsuarioId=" + UsuarioId
            });
            return req;
        };
        this.GetALLEstadisticas = function (From, To, UsuarioId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSST.php?From2=" + From + "&To2=" + To + "&UsuarioId2=" + UsuarioId
            });
            return req;
        };
        this.GetALLReportesDiariosByUsuarioId = function (UsuarioId) {
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/ReporteSST.php?UsuarioIdPlantaElectrica=" + UsuarioId
            });
            return req;
        };
    }]);


