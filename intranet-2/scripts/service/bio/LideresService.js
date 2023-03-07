app.service("LideresService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
        this.getLideres = function (Year, Mes, Tipo) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/Persona.php?LiderAll=True&Mes=${Mes}&Year=${Year}&Tipo=${Tipo}`);
            return req;
        };

        this.getListadoE_S = function (Year, Mes, Tipo, PersonaId, TipoTurno) {
            cfpLoadingBar.start();
            var req = $http.get(`/Polivalente/api/Persona.php?PersonaId_es=${PersonaId}&TipoTurno_es=${TipoTurno}&Mes_es=${Mes}&Year_es=${Year}&Tipo_es=${Tipo}`);
            return req;
        };
        
        this.getHorarioColaboradores= function (PersonaId, Mes, Year) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: `/Polivalente/api/Persona.php?LiderId_horario=${PersonaId}&Mes_horario=${Mes}&Year_horario=${Year}`,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                cache: false,
            });
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
        
        this.getESLideres = function (Year, Mes, Dia) {
            cfpLoadingBar.start();
            var req = $http({
                method: 'GET',
                url: `/Polivalente/api/Persona.php?Dia_lid=${Dia}&Mes_lid=${Mes}&Year_lid=${Year}`,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                cache: false,
            });
            return req;
        };

    }]);

