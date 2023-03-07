app.service("PermisoCTService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.getPermisoByPersonaId_Fecha = function (PersonaId, FechaInicio, FechaFin) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/Persona.php?PersonaId_eva=${PersonaId}&FechaInicio_eva=${FechaInicio}&FechaFin_eva=${FechaFin}`);
            return req;
        };
        this.getPersonas = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php");
            return req;
        };
        this.AutorizarPermiso = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/Persona.php",
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
        this.getPermisoByLiderId = function (LiderId, Mes, Year) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php?LiderId_permiso=" + LiderId + '&Mes_permiso=' + Mes + '&Year_permiso=' + Year);
            return req;
        };
        
        this.getPermisosBySedeIdAndMes = function (SedeId, Mes, Year) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/Persona.php?SedeId_persede=${SedeId}&Mes_persede=${Mes}&Year_persede=${Year}`);
            return req;
        };
        this.getPermisos = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php?AllPermiso=True");
            return req;
        };
        this.getPermisosLimite = function (Limite) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php?PermisoLimite=" + Limite);
            return req;
        };
        this.getPermisoByCodigoTarjeta = function (CodigoTarjeta_gh) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php?CodigoTarjeta_gh=" + CodigoTarjeta_gh.toLocaleString());
            return req;
        };
        this.getPermisoByPermisoId = function (PermisoId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php?PermisoId=" + PermisoId);
            return req;
        };
        this.getPersonaByDocumento = function (Documento_especial) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php?Documento_persona=" + Documento_especial);
            return req;
        };
        this.postPermiso = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'POST',
                url: "/Polivalente/api/Persona.php",
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
        this.putPersona = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'PUT',
                url: "/Polivalente/api/Persona.php",
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
        this.DeletePermiso = function (obj) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'DELETE',
                url: "/Polivalente/api/Persona.php",
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
    }]);




