app.controller('VerPSOLCtrl', ["$scope", "$rootScope", "ProcesosService", "SesionService",
  function ($scope, $rootScope, ProcesosService, SesionService) {
    // VARIABLES
    let vm = $scope
    vm.PREFIJO = ''
    vm.UProceso = {}
    vm.Seguimientos = []
    vm.Orden = 0
    const u = SesionService.get("UserData_Polivalente")
    if (u != undefined) {
      $rootScope.username = u
    } else {
      vm.PREFIJO = ''
      alert("No existe un usuario")
    }

    function _init() {
      setTimeout(() => {
        if (window.ProSOL) {
          vm.PREFIJO = window.ProSOL.PREFIJO // SolicitudId
          GetProcesoById(window.ProSOL.ProcesoId)
        } else {
          window.close()
        }
        vm.$apply()
      }, 1000)
    }
    // EVENTOS
    vm.changeView = function (o) {
      vm.Orden = o
    }
    vm.OpenURL = function (url, type) {
      const strWindowFeatures = 'location=yes,height=800,width=1600,scrollbars=yes,status=yes'
      var blob = b64toBlob(url.split('base64,')[1], type)
      var blobUrl = window.URL.createObjectURL(blob)
      var w1 = window.open('', '_blank', strWindowFeatures)
      w1.location = blobUrl
    }
    // CONSULTAS
    function GetProcesoById(ProcesoId) {
      vm.Seguimientos = []
      ProcesosService.GetProcesosById(ProcesoId).then(function (p) {
        vm.UProceso = angular.copy(p.data)
        vm.Orden = parseInt(vm.UProceso.OrdenEnCurso)

      }).then(() => {
        ProcesosService.GetProcesoData(ProcesoId).then(function (d) {
          vm.UProceso.FlujoTrabajo = d.data
          for (var i in vm.UProceso.FlujoTrabajo) {
            for (var k in vm.UProceso.FlujoTrabajo[i].Seguimiento) {
              vm.Seguimientos.push(vm.UProceso.FlujoTrabajo[i].Seguimiento[k])
            }
          }
          vm.UProceso.DatosFormulario = JSON.parse(vm.UProceso.DatosFormulario)
        })
      })
    }
    _init()

    // ONs
    vm.$on('$destroy', function () {
      vm = null
      $scope = null
    })
    // HELPERS
    function b64toBlob(b64Data, contentType, sliceSize) {
      contentType = contentType || ''
      sliceSize = sliceSize || 512

      var byteCharacters = atob(b64Data)
      var byteArrays = []

      for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
        var slice = byteCharacters.slice(offset, offset + sliceSize)

        var byteNumbers = new Array(slice.length)
        for (var i = 0; i < slice.length; i++) {
          byteNumbers[i] = slice.charCodeAt(i)
        }

        var byteArray = new Uint8Array(byteNumbers)

        byteArrays.push(byteArray)
      }

      var blob = new Blob(byteArrays, {
        type: contentType
      })
      return blob
    }
  }
])