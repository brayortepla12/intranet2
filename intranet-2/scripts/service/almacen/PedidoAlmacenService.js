app.service('PedidoAlmacenService', [
  '$http',
  'cfpLoadingBar',
  function ($http, cfpLoadingBar) {
    this.PostPedidoAlmacen = function (obj) {
      cfpLoadingBar.start()
      var req = $http({
        method: 'POST',
        url: '/Polivalente/api/PedidoAlmacen.php',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        transformRequest: function (obj) {
          var str = []
          for (var p in obj)
            str.push(encodeURIComponent(p) + '=' + encodeURIComponent(obj[p]))
          return str.join('&')
        },
        data: obj
      })
      return req
    }

    this.PutPedidoAlmacen = function (obj) {
      cfpLoadingBar.start()
      var req = $http({
        method: 'PUT',
        url: '/Polivalente/api/PedidoAlmacen.php',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        transformRequest: function (obj) {
          var str = []
          for (var p in obj)
            str.push(encodeURIComponent(p) + '=' + encodeURIComponent(obj[p]))
          return str.join('&')
        },
        data: obj
      })
      return req
    }
    this.DeletePedidoAlmacen = function (obj) {
      cfpLoadingBar.start()
      var req = $http({
        method: 'DELETE',
        url: '/Polivalente/api/PedidoAlmacen.php',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        transformRequest: function (obj) {
          var str = []
          for (var p in obj)
            str.push(encodeURIComponent(p) + '=' + encodeURIComponent(obj[p]))
          return str.join('&')
        },
        data: obj
      })
      return req
    }
    this.getAllPedidoAlmacenBySede = function (UserId, Estado) {
      cfpLoadingBar.start()
      var req = $http.get(
        `/Polivalente/api/PedidoAlmacen.php?Usuario_sede=${UserId}&Estado_sede=${Estado}`
      )
      return req
    }

    this.getAllPedidoAlmacenBySede_2 = function (UserId, Estado, Tipo) {
      cfpLoadingBar.start()
      var req = $http.get(
        '/Polivalente/api/PedidoAlmacen.php?Usuario_sede_2=' +
          UserId +
          '&Estado_2=' +
          Estado +
          '&Tipo=' +
          Tipo
      )
      return req
    }
    this.getAllPedidoAlmacenBySede_2Pedido = function (UserId, Estado, Tipo) {
      cfpLoadingBar.start()
      var req = $http.get(
        '/Polivalente/api/PedidoAlmacen.php?Usuario_sede_2Pedido=' +
          UserId +
          '&Estado_2Pedido=' +
          Estado +
          '&TipoPedido=' +
          Tipo
      )
      return req
    }

    this.getAllPedidoAlmacen = function () {
      cfpLoadingBar.start()
      var req = $http.get('/Polivalente/api/PedidoAlmacen.php')
      return req
    }

    this.getItemsPedidoAlmacen = function (PedidoAlmacenId_item) {
      cfpLoadingBar.start()
      var req = $http.get(
        '/Polivalente/api/PedidoAlmacen.php?PedidoAlmacenId_item=' +
          PedidoAlmacenId_item
      )
      return req
    }
    this.getItemsPedidoAlmacenAdmin = function (PedidoAlmacenId_item) {
      cfpLoadingBar.start()
      var req = $http.get(
        '/Polivalente/api/PedidoAlmacen.php?PedidoAlmacenId_itemAdmin=' +
          PedidoAlmacenId_item
      )
      console.log(req)
      return req
    }

    this.getItemsPedidoAlmacenAdminRepuesto = function (PedidoAlmacenId_item) {
      cfpLoadingBar.start()
      var req = $http.get(
        '/Polivalente/api/PedidoAlmacen.php?PedidoAlmacenId_itemAdminRepuesto=' +
          PedidoAlmacenId_item
      )
      console.log(req)
      return req
    }

    this.GetBySolicitanteId = function (UserId) {
      cfpLoadingBar.start()
      var req = $http.get('/Polivalente/api/PedidoAlmacen.php?UserId=' + UserId)
      return req
    }

    this.GetBySolicitanteIdVer2 = function (UserId, Tipo) {
      cfpLoadingBar.start()
      var req = $http.get(
        '/Polivalente/api/PedidoAlmacen.php?SolicitanteId=' +
          UserId +
          '&Tipo1=' +
          Tipo
      )
      return req
    }

    this.GetBySolicitanteIdVer2Repuesto = function (UserId, Tipo) {
      cfpLoadingBar.start()
      var req = $http.get(
        '/Polivalente/api/PedidoAlmacen.php?SolicitanteIdRepuesto=' +
          UserId +
          '&Tipo1Repuesto=' +
          Tipo
      )
      return req
    }

    this.GetById = function (ID) {
      cfpLoadingBar.start()
      var req = $http.get(
        '/Polivalente/api/PedidoAlmacen.php?PedidoAlmacenId=' + ID
      )
      return req
    }

    this.GetPedidoById_sm = function (ID) {
      cfpLoadingBar.start()
      var req = $http.get(
        '/Polivalente/api/PedidoAlmacen.php?Pedido_sm_Id=' + ID
      )
      return req
    }

    this.GetFileExcel = function (
      From,
      To,
      SedeId,
      ServicioId,
      Tipo,
      tipoSolicitud
    ) {
      window.open(
        '/Polivalente/api/Relacion.php?From=' +
          From +
          '&To=' +
          To +
          '&SedeId=' +
          SedeId +
          '&ServicioId=' +
          ServicioId +
          '&Tipoe=' +
          Tipo +
          '&TipoSolicitud=' +
          tipoSolicitud
      )
    }

    this.GetEstadisticaPedidos = function (From, To, SedeId, ServicioId, Tipo) {
      cfpLoadingBar.start()
      var req = $http.get(
        '/Polivalente/api/Relacion.php?Estadistica=True&From2=' +
          From +
          '&To2=' +
          To +
          '&SedeId=' +
          SedeId +
          '&ServicioId=' +
          ServicioId +
          '&Tipo_e=' +
          Tipo
      )
      return req
    }

    this.GetEstadisticaPedidosRepuestos = function (
      From,
      To,
      SedeId,
      ServicioId,
      Tipo
    ) {
      cfpLoadingBar.start()
      var req = $http.get(
        '/Polivalente/api/Relacion.php?EstadisticaRepuesto=True&From2Repuesto=' +
          From +
          '&To2Repuesto=' +
          To +
          '&SedeIdRepuesto=' +
          SedeId +
          '&ServicioIdRepuesto=' +
          ServicioId +
          '&Tipo_eRepuesto=' +
          Tipo
      )
      return req
    }
  }
])
