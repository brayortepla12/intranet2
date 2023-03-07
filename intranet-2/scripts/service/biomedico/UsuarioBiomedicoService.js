app.service("UsuarioBiomedicoService",[ "$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
    var base = "/Biomedico/api/Usuario.php";
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
    this.GetALLusuarios= function (key, Clave) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'GET',
                    url: base + '?U_Key=' + key + "&Clave=" + Clave,
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
    
    this.IsInDB = function (Email) {
        cfpLoadingBar.start();
        var req = $http({
                    method: 'GET',
                    url: base + '?Email=' + Email
                  });
        return req;
    };
}]);



