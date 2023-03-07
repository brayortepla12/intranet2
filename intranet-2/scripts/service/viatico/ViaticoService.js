app.service("ViaticoService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
  this.GetSolicitudesPendientes = function (Mes, Year) {
    const req = $http({
      method: 'GET',
      url: `/Polivalente/api/viatico/Viatico.php?Mes_ps=${Mes}&Year_ps=${Year}`
    })
    return req
  }
  this.GetSolicitudesAutorizar = function (Mes, Year) {
    const req = $http({
      method: 'GET',
      url: `/Polivalente/api/viatico/Viatico.php?Mes_auth=${Mes}&Year_auth=${Year}`
    })
    return req
  }
  this.GetSolicitudesViaticoByUsuario = function (UsuarioId) {
    const req = $http({
      method: 'GET',
      url: `/Polivalente/api/viatico/Viatico.php?UsuarioSolicitaId=${UsuarioId}`
    })
    return req
  }
  this.GetSolicitudViaticoById = function (Id) {
    const req = $http({
      method: 'GET',
      url: `/Polivalente/api/viatico/Viatico.php?SolicitudId=${Id}`
    })
    return req
  }
  this.GetPreSolicitudViaticoById = function (PresolicitudId) {
    const req = $http({
      method: 'GET',
      url: `/Polivalente/api/viatico/Viatico.php?PresolicitudId=${PresolicitudId}`
    })
    return req
  }
  this.GetConceptos = function (Id) {
    const req = $http({
      method: 'GET',
      url: `/Polivalente/api/viatico/Viatico.php?Conceptos=True`
    })
    return req
  }
  this.GetDepartamentos = function () {
    const req = $http({
      method: 'GET',
      url: `/Polivalente/api/viatico/Viatico.php?Dpt=True`
    })
    return req
  }
  this.GetMunicipioByDptId = function (DptId) {
    const req = $http({
      method: 'GET',
      url: `/Polivalente/api/viatico/Viatico.php?DptId=${DptId}`
    })
    return req
  }
  this.postViaticos = (_obj) => {
    cfpLoadingBar.start()
    const req = $http({
        method: 'POST',
        url: "/Polivalente/api/viatico/Viatico.php",
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        transformRequest: function (obj) {
            const str = []
            for (const p in obj)
                str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]))
            return str.join("&")
        },
        data: _obj
    })
    return req
  }
  this.put = (_obj) => {
    cfpLoadingBar.start()
    const req = $http({
        method: 'PUT',
        url: "/Polivalente/api/viatico/Viatico.php",
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        transformRequest: function (obj) {
            const str = []
            for (const p in obj)
                str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]))
            return str.join("&")
        },
        data: _obj
    })
    return req
  }
}])