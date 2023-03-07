app.service("ColaboradoresService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        
        this.getPersonaByUser = function (Usuario) {
            cfpLoadingBar.start();
            var req = $http.get("/Polivalente/api/Persona.php?Usuario_persona=" + Usuario);
            return req;
        };
        this.getHorarioByColaboradorId = function (Year, Mes, ColaboradorId) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/Persona.php?ColaboradorId_hor=${ColaboradorId}&Mes_hor=${Mes}&Year_hor=${Year}`);
            return req;
        };
        this.getColaboradores = function (Year, Mes, Tipo) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/Persona.php?ColaboradorAll=True&Mes=${Mes}&Year=${Year}&Tipo=${Tipo}`);
            return req;
        };
        
        this.getVariablesByUsuario = (UsuarioOrPersona) => {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/Persona.php?VariablesUP=${UsuarioOrPersona}`);
            return req;
        };
        
        this.getColaboradoresByLiderId = function (Year, Mes, Tipo, LiderId) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/Persona.php?Mes_plid2=${Mes}&Year_plid2=${Year}&Tipo_plid2=${Tipo}&LiderId_plid2=${LiderId}`);
            return req;
        };
        
        this.getColaboradoresByLider = function (Year, Mes, Tipo, UsuarioLider) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/Persona.php?Mes_plid=${Mes}&Year_plid=${Year}&Tipo_plid=${Tipo}&UsuarioLider_plid=${UsuarioLider}`);
            return req;
        };

        this.getListadoE_S = function (Year, Mes, Tipo, PersonaId, TipoTurno) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/Persona.php?PersonaId_es=${PersonaId}&TipoTurno_es=${TipoTurno}&Mes_es=${Mes}&Year_es=${Year}&Tipo_es=${Tipo}`);
            return req;
        };

        this.getListadoES = function (Year, Mes, PersonaId) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: `/Polivalente/api/Persona.php?PersonaId_es2=${PersonaId}&Mes_es2=${Mes}&Year_es2=${Year}`,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                cache: false,
            });
            return req;
        };
        
        this.getESColaboradores = function (Year, Mes, Dia) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: `/Polivalente/api/Persona.php?Dia_col=${Dia}&Mes_col=${Mes}&Year_col=${Year}`,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                cache: false,
            });
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

