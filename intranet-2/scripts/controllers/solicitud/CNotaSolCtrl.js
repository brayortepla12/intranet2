app.controller('CNotaSolCtrl', ["$scope", "$rootScope", "ProcesosService", "ProtocoloService",
  "$builder", "$validator", "SedeService", "ServicioService", "SOLFactory", "SesionService",
  function ($scope, $rootScope, ProcesosService, ProtocoloService, $builder,
    $validator, SedeService, ServicioService, SOLFactory, SesionService) {
    // VARIABLES
    let vm = $scope
    const u = SesionService.get("UserData_Polivalente")
    if (u != undefined) {
      $rootScope.username = u
    } else {
      vm.Sol = {}
      vm.PREFIJO = ''
      alert("No existe un usuario")
    }
    vm.IsLoading = false
    vm.Descripcion = ''
    let data = {}
    const tipo = window.TIPO
    const PREFIJO = window.PREFIJO
    if (tipo === 'PC-admin') {
      GetNotasByProcesoId(window.PROCESOID)
    } else {
      data = window.SOL
      GetNotas()
    }
    // EVENTOS
    vm.CrearNota = () => {
      if (vm.IsLoading) {
        return false
      } else if (vm.Descripcion.length !== 0) {
        const obj = {
          Nota: JSON.stringify({
            PersonaId: u.PersonaId,
            UsuarioVerificadorId: u.UserId,
            Nombres: u.NombreCompleto,
            Descripcion: vm.Descripcion,
            SolicitudId: tipo != 'PC-admin' ? data.SolicitudId : null,
            ProcesoId: tipo != 'PC-admin' ? data.ProcesoId : window.PROCESOID,
            Tipo: tipo,
            PREFIJO: PREFIJO
          })
        }
        vm.IsLoading = true
        ProcesosService.postProcesos(obj).then(r => {
          vm.IsLoading = false
          if (Array.isArray(r.data)){
            window.opener.notify()
            window.close()
          } else {
            swal("Error!", r.data, 'error')
          }
        }).catch(err => { vm.IsLoading = false })
      }
    }
    function GetNotas() {
      ProcesosService.GetNotas(data.SolicitudId).then(r => {
        vm.Notas = Array.isArray(r.data) ? r.data : []
      })
    }
    function GetNotasByProcesoId(ProcesoId) {
      ProcesosService.GetNotasByprocesoId(ProcesoId).then(r => {
        vm.Notas = Array.isArray(r.data.data) ? r.data.data : []
      })
    }
    // HELPERS
    vm.$on('$destroy', function () {
      vm = null
      $scope = null
    })
  }
])