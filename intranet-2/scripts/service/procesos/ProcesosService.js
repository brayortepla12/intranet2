app.service("ProcesosService", ["$http", function ($http) {
  this.postProcesos = function (obj) {
    var req = $http({
      method: 'POST',
      url: "/Polivalente/api/Procesos.php",
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
  this.DeleteProcesos = function (obj) {
    var req = $http({
      method: 'DELETE',
      url: "/Polivalente/api/Procesos.php",
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
  this.putProcesos = function (obj) {
    var req = $http({
      method: 'PUT',
      url: "/Polivalente/api/Procesos.php",
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
  this.GetAllProcesos = function (UsuarioId) {
    var req = $http({
      method: 'GET',
      url: "/Polivalente/api/Procesos.php?UserId=" + UsuarioId
    });
    return req;
  };

  this.getProcesoOrdenCompra = function () {
    var req = $http({
      method: 'GET',
      url: "/Polivalente/api/Procesos.php?ProcesosOrdenCompra=True"
    });
    return req;
  };

  this.GetProcesosForAuditoria = function (Estado) {
    var req = $http({
      method: 'GET',
      url: "/Polivalente/api/Procesos.php?EstadoAutitoria=" + Estado
    });
    return req;
  };

  this.GetPrefijoByReporteId = function (ReporteId) {
    var req = $http({
      method: 'GET',
      url: "/Polivalente/api/Procesos.php?PrefijoReporteId=" + ReporteId
    });
    return req;
  };

  this.GetProcesosByVerificador = function (UsuarioId, Estado) {
    var req = $http({
      method: 'GET',
      url: "/Polivalente/api/Procesos.php?VerificadorId=" + UsuarioId + "&Estado=" + Estado
    });
    return req;
  };

  this.GetProcesosFinalizadosByVerificador = function (UsuarioId, Estado, Mes, Year) {
    var req = $http({
      method: 'GET',
      url: "/Polivalente/api/Procesos.php?VerificadorId_f=" + UsuarioId + "&Estado_f=" + Estado + "&Mes_f=" + Mes + "&Year_f=" + Year
    });
    return req;
  };

  this.GetProcesosById = function (ProcesosId) {
    var req = $http({
      method: 'GET',
      url: "/Polivalente/api/Procesos.php?ProcesoId=" + ProcesosId
    });
    return req;
  };

  this.GetProcesoData = function (ProcesosId) {
    var req = $http({
      method: 'GET',
      url: "/Polivalente/api/Procesos.php?ProcesoId_data=" + ProcesosId
    });
    return req;
  };
  this.GetNotas = function (SolicitudMantId) {
    var req = $http({
      method: 'GET',
      url: "/Polivalente/api/Procesos.php?SolicitudMantId=" + SolicitudMantId
    });
    return req;
  };
  this.GetNotasByprocesoId = function (ProcesoId) {
    var req = $http({
      method: 'GET',
      url: "/Polivalente/api/Procesos.php?NotaProcesoId=" + ProcesoId
    });
    return req;
  };
}]);