app.service("SolicitudAmbulanciaService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.postSolicitud = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/SolicitudAmbulancia.php",
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
        this.PutSolicitud = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/SolicitudAmbulancia.php",
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
        this.DeleteSolicitud = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'DELETE',
                url: "/Polivalente/api/SolicitudAmbulancia.php",
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
        
        this.GetReporteBySolicitudId = function (SolicitudMantenimientoId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: `/Polivalente/api/SolicitudAmbulancia.php?Reporte_SolicitudMantenimientoId=${SolicitudMantenimientoId}`
            });
            return req;
        };
        this.GetFacturaBySolicitudId = function (SolicitudMantenimientoId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: `/Polivalente/api/SolicitudAmbulancia.php?SolicitudMantenimientoId=${SolicitudMantenimientoId}`
            });
            return req;
        };
        this.getSolicitudes = function (Year, Mes, Estado, Placa) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: `/Polivalente/api/SolicitudAmbulancia.php?Solicitudes=True&Year=${Year}&Mes=${Mes}&Estado=${Estado}&Placa=${Placa}`
            });
            return req;
        };
        this.getItem = function () {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/SolicitudAmbulancia.php?Item=True"
            });
            return req;
        };
        this.getProveedores = function () {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: "/Polivalente/api/SolicitudAmbulancia.php?Proveedor=True"
            });
            return req;
        };
    }]);







