app.service("PersonaService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.getFileText = function (JefeId) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/horarios_temp/${JefeId}.txt`);
            return req;
        };
        
        this.VerificarRegUser = function (UsuarioId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php?UsuarioIdRegUser=" + UsuarioId);
            return req;
        };
        this.getPersonas = function (Estado) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php?Estado_persona=" + Estado);
            return req;
        };
        this.getPersonasLite = function (Estado) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php?Estado_persona_lite=" + Estado);
            return req;
        };
        this.getCambioHorario = function (SedeId, Mes, Year) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/Persona.php?SedeId_cambhor=${SedeId}&Mes_cambhor=${Mes}&Year_cambhor=${Year}`);
            return req;
        };
        this.getPersonasActivas = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php?PersonasActivas=True");
            return req;
        };
        this.getPersonaByDocumento = function (Documento_especial) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php?Documento_persona=" + Documento_especial);
            return req;
        };
        this.getPersonaById = function (PersonaId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php?PersonaId=" + PersonaId);
            return req;
        };
        this.getPersonaByLider= function (Lider) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/Persona.php?Usuario_lider=${Lider}`);
            return req;
        };
        this.getHorario = function (TurnoId) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/Persona.php?TurnoId=${TurnoId}`);
            return req;
        };
        this.getTurnosByPersonaId = function (PersonaId) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php?TurnoPersona=" + PersonaId);
            return req;
        };
        this.getTurnos = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php?Turnos=True");
            return req;
        };
        this.getLideres = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php?Lider=True");
            return req;
        };
        this.getCargos = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php?Cargo=True");
            return req;
        };
        this.getDispositivos = function () {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php?Dispositivo=True");
            return req;
        };
        this.getDispositivoByName = function (Dispositivo) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/Persona.php?NombreDispositivo=${Dispositivo}`);
            return req;
        };
        this.getPersonaByCodigo = function (Codigo, Dispositivo) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/Persona.php?Codigo=${Codigo}&Dispositivo=${Dispositivo}`);
            return req;
        };
        this.postPersona = function (obj) {
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
    }]);

