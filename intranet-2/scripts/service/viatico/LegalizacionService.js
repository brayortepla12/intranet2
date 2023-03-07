app.service("LegalizacionService", ["$http", "cfpLoadingBar", function ($http, cfpLoadingBar) {
  this.getSolicitudesPendienteLegalizacion = function (Mes, Year, Tipo, UsuarioId) {
    const req = $http({
      method: 'GET',
      url: `/Polivalente/api/viatico/Legalizacion.php?Mes_leg=${Mes}&Year_leg=${Year}&Tipo_leg=${Tipo}&UsuarioId_leg=${UsuarioId}`
    })
    return req
  }
  this.getLegalizacion = function (Mes, Year, UsuarioId) {
    const req = $http({
      method: 'GET',
      url: `/Polivalente/api/viatico/Legalizacion.php?Mes_rl=${Mes}&Year_rl=${Year}&UsuarioId_rl=${UsuarioId}`
    })
    return req
  }
  this.getConceptosByLegalizacionId = function (LegalizacionId) {
    const req = $http({
      method: 'GET',
      url: `/Polivalente/api/viatico/Legalizacion.php?LegalizacionId_dl=${LegalizacionId}`
    })
    return req
  }
  this.getAnexoByDetalleLegalizacionId = function (DetalleLegalizacionId) {
    const req = $http({
      method: 'GET',
      url: `/Polivalente/api/viatico/Legalizacion.php?DetalleLegalizacionId_dl=${DetalleLegalizacionId}`
    })
    return req
  }
  this.post = (_obj) => {
    cfpLoadingBar.start()
    const req = $http({
      method: 'POST',
      url: "/Polivalente/api/viatico/Legalizacion.php",
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
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
      url: "/Polivalente/api/viatico/Legalizacion.php",
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
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