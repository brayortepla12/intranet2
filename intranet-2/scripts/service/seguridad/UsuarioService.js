app.service("UsuarioService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    var base = "/Polivalente/api/Usuario.php";
    this.PostUsuario= function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'POST',
                    url: base,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    transformRequest: function(obj) {
                        var str = [];
                        for(var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                        return str.join("&");
                    },
                    data: obj
                  });
        return req;
    };
    this.PutUsuario= function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'PUT',
                    url: base,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    transformRequest: function(obj) {
                        var str = [];
                        for(var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                        return str.join("&");
                    },
                    data: obj
                  });
        return req;
    };
    this.Getpermisos= function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'POST',
                    url: base,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    transformRequest: function(obj) {
                        var str = [];
                        for(var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                        return str.join("&");
                    },
                    data: obj
                  });
        return req;
    };
    this.GetALLpermisos= function (obj) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'POST',
                    url: base,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    transformRequest: function(obj) {
                        var str = [];
                        for(var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                        return str.join("&");
                    },
                    data: obj
                  });
        return req;
    };
    this.GetALLusuarios= function (PersonaId, Email) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'GET',
                    url: base + '?U_Key=' + PersonaId + "&Clave=" + Email,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    transformRequest: function(obj) {
                        var str = [];
                        for(var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                        return str.join("&");
                    }
                  });
        return req;
    };
    this.GetUsuariosCol = function (key, Email) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'GET',
                    url: base + '?LiderPId=' + key + "&PEmail=" + Email,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    transformRequest: function(obj) {
                        var str = [];
                        for(var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                        return str.join("&");
                    }
                  });
        return req;
    };
    
    this.GetUsuariosCT= function () {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'GET',
                    url: base + `?U_CT=True`
                  });
        return req;
    };
    this.GetUsuariosCM = function (key, Clave) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'GET',
                    url: base + '?U_Key_CM=' + key + "&Clave_CM=" + Clave,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    transformRequest: function(obj) {
                        var str = [];
                        for(var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                        return str.join("&");
                    },
                    data: {U_Key:key}
                  });
        return req;
    };
    this.GetALLusuariosByServicio = function (ServicioId) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'GET',
                    url: base + '?ServicioId=' + ServicioId
                  });
        return req;
    };
    
    this.GetUsuarioById = function (UsuarioId) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'GET',
                    url: base + '?UsuarioId=' + UsuarioId
                  });
        return req;
    };
    
    this.GetUsuarioWithPlantilla = function (ServicioId) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'GET',
                    url: base + '?ServicioId_plantilla=' + ServicioId
                  });
        return req;
    };
    
    this.IsInDB = function (Email) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'GET',
                    url: base + '?Email=' + Email
                  });
        return req;
    };
    
    this.DoResetPass = function (Email) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'GET',
                    url: base + '?Email_reset=' + Email
                  });
        return req;
    };
    
    this.VerificarDocumento = (Documento) => {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'GET',
                    url: base + '?DocumentoCT=' + Documento
                  });
        return req;
    };
}]);



