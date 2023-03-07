app.service("SolicitudService", ["$http", function ($http) {
  this.postSolicitud = function (obj) {
    var req = $http({
      method: 'POST',
      url: "/Polivalente/api/Solicitud.php",
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
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
  this.putSolicitud = function (obj) {
    var req = $http({
      method: 'PUT',
      url: "/Polivalente/api/Solicitud.php",
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
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
  this.GetSolicitudesPolByUsuario = function (UsuarioId, Prefijo) {
    var req = $http({
      method: 'GET',
      url: `/Polivalente/api/Solicitud.php?UsuarioId_pol=${UsuarioId}&PREFIJO=${Prefijo}`
    });
    return req;
  };
  this.GetTotalSolicitudesActivas = function (prefijo = true) {
    var req = $http({
      method: 'GET',
      url: `/Polivalente/api/Solicitud.php?PREFIJO_TS=${prefijo}`
    });
    return req;
  };
  this.GetSolicitudPolById = function (Id, prefijo) {
    var req = $http({
      method: 'GET',
      url: `/Polivalente/api/Solicitud.php?Pol_SolicitudId=${Id}&PREFIJO=${prefijo}`
    });
    return req;
  };
  this.GetEventoBySolicitudId_Pol = function (Id, Prefijo) {
    var req = $http({
      method: 'GET',
      url: `/Polivalente/api/Solicitud.php?Pol_EventoBySolicitudId=${Id}&PREFIJO=${Prefijo}`
    });
    return req;
  };
  this.GetReporteBySolicitudId = function (Id, Prefijo) {
    var req = $http({
      method: 'GET',
      url: `/Polivalente/api/Solicitud.php?SolicitudId_r=${Id}&PREFIJO_r=${Prefijo}`
    });
    return req;
  };
  this.GetProcesosBySolicitudId = function (Id, Prefijo) {
    var req = $http({
      method: 'GET',
      url: `/Polivalente/api/Solicitud.php?SolicitudId_p=${Id}&PREFIJO_p=${Prefijo}`
    });
    return req;
  };
  this.GetReporteExternoById_Pol = function (Id, prefijo) {
    var req = $http({
      method: 'GET',
      url: `/Polivalente/api/Solicitud.php?Pol_ReporteExternoId=${Id}&PREFIJO=${prefijo}`
    });
    return req;
  };
  this.GetAllSolicitudesPol = function (Key, prefijo, Mes, Year) {
    var req = $http({
      method: 'GET',
      url: `/Polivalente/api/Solicitud.php?Key_pol=${Key}&PREFIJO=${prefijo}&MES=${Mes}&YEAR=${Year}`
    });
    return req;
  };
  this.CountSolicitudes = function (UsuarioId) {
    var req = $http({
      method: 'GET',
      url: "/Polivalente/api/Solicitud.php?Cuenta=" + 1 + "&UsuarioId2=" + UsuarioId // para acceder
    });
    return req;
  };
}]);