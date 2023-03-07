app.controller('CPSOLCtrl', ["$scope", "$rootScope", "ProcesosService", "ProtocoloService",
  "$builder", "$validator", "SedeService", "ServicioService", "SOLFactory", "SesionService",
  function ($scope, $rootScope, ProcesosService, ProtocoloService, $builder,
    $validator, SedeService, ServicioService, SOLFactory, SesionService) {
    // VARIABLES
    let vm = $scope
    vm.CargarForm2 = false
    const u = SesionService.get("UserData_Polivalente")
    if (u != undefined) {
      $rootScope.username = u
    } else {
      vm.Sol = {}
      vm.PREFIJO = ''
      alert("No existe un usuario")
    }
    vm.Protocolos = []
    vm.Sedes = []
    vm.Servicios = []
    $builder.forms['JSON'] = null
    vm.Orden = 0
    vm.Seguimientos = []
    vm.Proceso = {}
    vm.input = []
    vm.inputEdit = []
    vm.IsLoading = false
    const data = JSON.parse(window.SOL)
    const UsuarioId = data.PREFIJO === 'biomedicos' ? u.UsuarioBiomedicoId : u.UserId
    _init()

    //<editor-fold defaultstate="collapsed" desc="Botones">
    vm.ChangeProtocolo = function () {
      vm.CargarForm = false
      for (var k in vm.Protocolos) {
        if (vm.Protocolos[k].ProtocoloId == vm.Proceso.ProtocoloId) {
          $builder.forms['JSON'] = JSON.parse(vm.Protocolos[k].Formulario)
          //                    vm.$apply()
          vm.Proceso.DatosFormulario = vm.Protocolos[k].Formulario
          vm.CargarForm = true
        }
      }
    }
    /**
     * Enviar solicitud al server
     */
    vm.EnviarSolicitud = function () {
      if (vm.IsLoading) {
        return false
      }
      if (vm.Datos.$valid && vm.Proceso.ServicioId !== '--') {
        var count = 0
        for (var i = 0; i < vm.input.length; i++) {
          var x = vm.input[i].label
          if (document.getElementById(vm.input[i].label) != null) {
            var requerido = $(document.getElementById(vm.input[i].label)).attr("validator-required")
            if (requerido === "true") {
              count++
            }
          }
        }
        $validator.validate($scope, 'JSON').success(function () {
          if (count != 0) {
            swal("Error!", 'Faltan por llenar campos obligatorios. ', 'error')
          } else {
            vm.Proceso.DatosFormulario = angular.toJson(vm.input)
            vm.Proceso.EventoSolicitudId = SOLFactory.data.EventoSolicitudId
            vm.Proceso.Prefijo = SOLFactory.data.PREFIJO
            vm.Proceso.SolicitudId = SOLFactory.data.Sol.SolicitudId
            var obj = {
              Proceso: angular.toJson([vm.Proceso])
            }
            vm.IsLoading = true
            ProcesosService.postProcesos(obj).then(function (d) {
              vm.IsLoading = false
              if (typeof d.data === "string") {
                swal("Error!", d.data, 'error')
              } else {
                swal("Enhorabuena!", "Se han guardado los datos satisfactoriamente", "success")
                window.opener.notify()
                window.close()
              }
            }).catch(error => {
              vm.IsLoading = false
            })
          }
        }).error(function () {
          swal("Error!", 'Faltan por llenar campos obligatorios. ', 'error')
        })
      }
    }
    vm.ChangeSede = function () {
      GetServicio()
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Consulta">

    function GetProtocolos() {
      ProtocoloService.GetAllProtocolo($rootScope.username.UserId).then(function (d) {
        console.log(d.data)
        vm.Protocolos = d.data
      })
    }

    function GetSede() {
      SedeService.getAllSedeByUserId_TA($rootScope.username.UserId, data.PREFIJO).then(function (c) {
        vm.Sedes = c.data
        GetServicio()
      })
    }

    function GetServicio() {
      ServicioService.getServicioBySedeWithTA(vm.Proceso.SedeId, $rootScope.username.UserId, data.PREFIJO).then(function (r) {
        vm.Servicios = r.data
        SOLFactory.data.Sol = data.Solicitud
        SOLFactory.data.PREFIJO = data.PREFIJO
        SOLFactory.data.EventoSolicitudId = window.EventoSolicitudId
        
      })
    }

    function _init() {
      GetProtocolos()
      vm.Proceso = {
        ProtocoloId: "",
        DatosFormulario: "",
        Nombre: "",
        ServicioId: angular.copy(data.Solicitud.ServicioId),
        SedeId: angular.copy(data.Solicitud.SedeId),
        SolicitanteId: $rootScope.username.UserId,
        CreatedBy: $rootScope.username.NombreCompleto
      }
      GetSede()
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    //</editor-fold>
    vm.$on('$destroy', function () {
      vm = null
      $scope = null
    })
  }
])