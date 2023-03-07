app.controller('TrasladarPlantillaCtrl', [
  '$scope',
  '$rootScope',
  '$filter',
  'SedeService',
  'ServicioService',
  'UsuarioService',
  'SesionService',
  'RelacionService',
  function (
    $scope,
    $rootScope,
    $filter,
    SedeService,
    ServicioService,
    UsuarioService,
    SesionService,
    RelacionService
  ) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    $scope.Usuarios = []
    $scope.Servicios = []
    $scope.SedeId = null
    $scope.ServicioId = null
    $scope.UsuarioOrigen = null
    $scope.UsuarioDestino = null
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Botones">
    $scope.ChangeSede = function () {
      GetServicio()
    }
    $scope.Clonar = () => {
      if ($scope.ServicioId && $scope.UsuarioOrigen && $scope.UsuarioDestino) {
        let o = {
          UsuarioOrigenId: $scope.UsuarioOrigen[0].UsuarioId,
          ServicioId: $scope.ServicioId,
          UsuarioDestinoId: $scope.UsuarioDestino[0].UsuarioId,
          CreatedBy: $rootScope.username.NombreCompleto
        }
        let obj = {
          ClonarPlantilla: JSON.stringify([o])
        }
        RelacionService.PostRelacion(obj).then(d => {
          if (typeof d.data != 'string') {
            swal(
              'Enhorabuena',
              'Se han guardado los cambios correctamente',
              'success'
            )
          } else {
            swal('Error', d.data, 'error')
          }
        })
      } else {
        swal('Error', 'Debes ingresar todos los campos.', 'error')
      }
    }

    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Watchers">
    $scope.$watch('UsuarioOrigen', function (newValue, oldValue) {
      if (newValue) {
        if (newValue[0]) {
          GetServicio()
        }
      }
    })
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="consultas">
    function GetUsuarios() {
      UsuarioService.GetALLusuarios(
        SesionService.get('UserData_Polivalente').key,
        SesionService.get('UserData_Polivalente').Email
      ).then(function (d) {
        $scope.Usuarios = d.data
      })
    }
    function GetServicio() {
      if ($scope.UsuarioOrigen.length > 0) {
        ServicioService.getServicioBySedeAndUserId(
          $scope.SedeId,
          $scope.UsuarioOrigen[0].UsuarioId
        ).then(function (c) {
          $scope.Servicios = $filter('orderBy')(c.data, 'Nombre')
          $scope.Relacion.ServicioId = $scope.Servicios[0].ServicioId
        })
      }
    }
    function GetSedes() {
      SedeService.getAllSede().then(function (c) {
        $scope.Sedes = c.data
        $scope.SedeId = c.data[0].SedeId
      })
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    //</editor-fold>
    function _init() {
      GetSedes()
      GetUsuarios()
    }
    _init()
  }
])
