app.service("TelService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
  this.getSolicitudesByUsuarioId = function (Dia, Mes, Year, UsuarioId) {
    cfpLoadingBar.start();
    var req = $http.get(`/Polivalente/api/tel/Tel.php?Dia=${Dia}&Mes=${Mes}&Year=${Year}&UsuarioId=${UsuarioId}`);
    return req;
  };
  this.GetSolicitudesByToken = function (token) {
    cfpLoadingBar.start();
    var req = $http.get(`/Polivalente/api/tel/Tel.php?token=${token}`);
    return req;
  };
  
  this.getSolicitudes = function (Dia, Mes, Year) {
    cfpLoadingBar.start();
    var req = $http.get(`/Polivalente/api/tel/Tel.php?Dia=${Dia}&Mes=${Mes}&Year=${Year}`);
    return req;
  };
  this.getHistorial = (TelefonoId) => {
    cfpLoadingBar.start();
    var req = $http.get(`/Polivalente/api/tel/Tel.php?HT=${TelefonoId}`);
    return req;
  };
  this.getSolicitudesBySolicitudId = function (SolicitudId) {
    cfpLoadingBar.start();
    var req = $http.get(`/Polivalente/api/tel/Tel.php?SolicitudId=${SolicitudId}`);
    return req;
  };
  this.getEntregaBySolicitudId = function (SolicitudId) {
    cfpLoadingBar.start();
    var req = $http.get(`/Polivalente/api/tel/Tel.php?ESolicitudId=${SolicitudId}`);
    return req;
  };
  this.EnviarNotificacion = function (EntregaId) {
    cfpLoadingBar.start();
    var req = $http.get(`/Polivalente/api/tel/Tel.php?NEntregaId=${EntregaId}`);
    return req;
  };
  this.getTelefonos = function (PersonaId) {
    cfpLoadingBar.start();
    var req = $http.get(`/Polivalente/api/tel/Tel.php?TELPerId=${PersonaId}`);
    return req;
  };
  this.getAllTelefonos = function () {
    cfpLoadingBar.start();
    var req = $http.get(`/Polivalente/api/tel/Tel.php?TELEFONOS=True`);
    return req;
  };
  this.getInventario = function () {
    cfpLoadingBar.start();
    var req = $http.get(`/Polivalente/api/tel/Tel.php?Inv=True`);
    return req;
  };
  this.postTel = function (obj) {
    cfpLoadingBar.start();
    var req = $http({
      method: 'POST',
      url: "/Polivalente/api/tel/Tel.php",
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      transformRequest: function (o) {
        var str = [];
        for (var p in o)
          str.push(encodeURIComponent(p) + "=" + encodeURIComponent(o[p]));
        return str.join("&");
      },
      data: obj
    });
    return req;
  };
  this.putTel = function (obj) {
    cfpLoadingBar.start();
    var req = $http({
      method: 'PUT',
      url: "/Polivalente/api/tel/Tel.php",
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      transformRequest: function (o) {
        var str = [];
        for (var p in o)
          str.push(encodeURIComponent(p) + "=" + encodeURIComponent(o[p]));
        return str.join("&");
      },
      data: obj
    });
    return req;
  };
}]);