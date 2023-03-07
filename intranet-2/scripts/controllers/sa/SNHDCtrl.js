app.controller('SNHDCtrl', ['$scope', '$rootScope', '$state', 'HDFactory', 'HDService',
  function ($scope, $rootScope, $state, HDFactory, HDService) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    let vm = $scope
    vm.NUEVOSPACIENTES = 0
    vm.IsLoading = false
    let DATE = moment()
    vm.FechaAyer = moment().subtract(1, 'days').format('YYYY-MM-DD')
    vm.Estado = 'TODOS'
    vm.Mes = DATE.format('M')
    vm.Year = DATE.format('YYYY')
    vm.Dia = DATE.format('D')
    vm.UltimoDiaMes = Number(DATE.endOf('month').format('DD'))
    vm.HDs = {}
    vm.PHD = {}
    vm.pHD = {}
    vm.LHD = false
    vm.NHD = false
    vm.UHD = false
    vm.PRINT = false
    vm.BP = false
    vm.ImpDis = 'Desayuno'
    vm.TOTALC = 0
    vm.TVA = 0
    vm.TVC = 0
    vm.Distribucion = []
    vm.NP = false
    vm.MP = false
    vm.NC = false
    vm.MPHD = {}
    // EVENTOS ATRAS
    vm.Atras = () => {
      vm.LHD = true
      vm.BP = false
      vm.NHD = false
      vm.UHD = false
      vm.NC = false
      vm.PRINT = false
    }
    vm.AtrasUHD = () => {
      vm.BP = false
      vm.NC = false
      vm.NP = false
      vm.MP = false
      vm.UHD = true
      $rootScope.$broadcast('reload-hd')
      vm.PRINT = false
    }
    // EVENTOS
    vm.NewHD = () => {
      vm.TOTALC = 0
      vm.LHD = false
      vm.NHD = true
      setTimeout(() => { // EMBARRADAS DE ANGULARJS :(
        $rootScope.$broadcast('init-nhd')
      },300)
    }
    vm.CrearHD = () => {
      if (vm.IsLoading) {
        return false
      }
      $rootScope.$broadcast("create-hd")
    }
    vm.AddPaciente = () => {
      if (vm.IsLoading) {
        return false
      }
      $rootScope.$broadcast("add-paciente")
    }
    vm.AddPaciente_BP = () => {
      if (vm.IsLoading) {
        return false
      }
      $rootScope.$broadcast("add-paciente-bp")
    }
    vm.UpdatePaciente = () => {
      if (vm.IsLoading) {
        return false
      }
      $rootScope.$broadcast('update-paciente')
    }
    vm.BuscarPaciente = () => {
      vm.BP = true
      $rootScope.$broadcast('init-bp')
    }
    vm.Imprimir = () => {
      vm.PRINT = true
      $rootScope.$broadcast('load-print', HDFactory.HD)
    }
    vm.OpenEditarHD = (HDId) => {
      vm.LHD = false
      vm.UHD = true
      $scope.$apply()
      setTimeout(() => { // EMBARRADAS DE ANGULARJS :(
        $rootScope.$broadcast('init-uhd', HDId)
      },300)
    }
    vm.NuevoPaciente = () => {
      if (vm.IsLoading) {
        return false
      }
      if (HDFactory.NUEVOSPACIENTES === 0) {
        swal("Error", "No existen pacientes por añadir.", "error")
        return false
      }
      $rootScope.$broadcast('view-new-paciente', HDFactory.HD)
      vm.NP = true
    }
    vm.ModificarPaciente = () => {
      if (vm.IsLoading) {
        return false
      }
      const MPHD = angular.copy(HDFactory.HD)
      let PModificar = MPHD.Detalles.filter(x => x.Continua && !x.Nuevo)
      console.log(MPHD)
      MPHD.Detalles = PModificar
      MPHD.Distribucion = GetDistribucion(MPHD)
      vm.MP = true
      $rootScope.$broadcast('mod-view-paciente', MPHD)
    }
    vm.GetHDietas = () => {
      UpdateFecha()
      if ($state.current.name === 'sa.solicitud_hd') {
        GetHDs()
      } else if ($state.current.name === 'sa.crear_hd') {
        GetHDsNutricion()
      }
    }
    vm.NuevaComida = () => {
      const NCHD = angular.copy(HDFactory.HD)
      const PC = NCHD.Detalles.filter(x => x.Continua || x.Nuevo)
      NCHD.Detalles = PC
      NCHD.Detalles.forEach(x => {
        x.Seleccionado = true
      })
      $rootScope.$broadcast('nc-view', NCHD)
      vm.NC = true
    }
    vm.SolicitarComida = () => {
      if (vm.IsLoading) {
        return false
      }
      $rootScope.$broadcast('guardar-comida')
    }
    // CONSULTAS
    function GetHDs() {
      vm.LHD = false
      vm.UHD = false
      vm.NHD = false
      HDService.getHDsByUsuarioId($rootScope.username.UserId, vm.Estado, vm.Dia, vm.Mes, vm.Year).then((d) => {
        vm.HDs = {
          data: [],
          aoColumns: [{
              mData: 'HDId'
            },
            {
              mData: 'Fecha'
            },
            {
              mData: 'SECTOR'
            },
            {
              mData: 'DESCRIPCION'
            },
            {
              mData: 'Estado'
            },
            {
              mData: 'Desayuno'
            },
            {
              mData: 'MM'
            },
            {
              mData: 'Almuerzo'
            },
            {
              mData: 'MT'
            },
            {
              mData: 'Cena'
            },
            {
              mData: 'MN'
            },
            {
              mData: 'Opciones'
            }
          ],
          "searching": true,
          "iDisplayLength": 25,
          "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": " No hay Items Registrados ",
            "infoFiltered": "(filtro de _MAX_ registros totales)",
            "search": " Filtrar : ",
            "oPaginate": {
              "sPrevious": "Anterior",
              "sNext": "Siguiente"
            }
          },
          "aaSorting": []
        }
        vm.HDs.data = SetFormat(d.data)
        vm.LHD = true
      })
    }
    function GetHDsNutricion() {
      vm.LHD = false
      vm.UHD = false
      vm.NHD = false
      HDService.getHDs(vm.Estado, vm.Dia, vm.Mes, vm.Year).then((d) => {
        vm.HDs = {
          data: [],
          aoColumns: [{
              mData: 'HDId'
            },
            {
              mData: 'Fecha'
            },
            {
              mData: 'SECTOR'
            },
            {
              mData: 'DESCRIPCION'
            },
            {
              mData: 'Estado'
            },
            {
              mData: 'Desayuno'
            },
            {
              mData: 'MM'
            },
            {
              mData: 'Almuerzo'
            },
            {
              mData: 'MT'
            },
            {
              mData: 'Cena'
            },
            {
              mData: 'MN'
            },
            {
              mData: 'Opciones'
            }
          ],
          "searching": true,
          "iDisplayLength": 25,
          "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": " No hay Items Registrados ",
            "infoFiltered": "(filtro de _MAX_ registros totales)",
            "search": " Filtrar : ",
            "oPaginate": {
              "sPrevious": "Anterior",
              "sNext": "Siguiente"
            }
          },
          "aaSorting": []
        }
        vm.HDs.data = SetFormat(d.data)
        vm.LHD = true
      })
    }

    function UpdateFecha() {
      $('#dia-select option:selected').removeAttr('selected')
      let f = moment(`${vm.Year}-${vm.Mes}-01`)
      vm.UltimoDiaMes = Number(f.endOf("month").format("DD"))
      if (vm.Dia > vm.UltimoDiaMes) {
        vm.Dia = Number(vm.UltimoDiaMes)
      }
    }

    function SetFormat(lst) {
      for (var i in lst) {
        lst[i].Opciones = ''
        lst[i].Opciones += '<a class=\'btn  btn-info btn-xs icon-only white\' onclick=\'angular.element(this).scope().OpenEditarHD(' + lst[i].HDId + ')\'><i class=\'fa fa-pencil\'></i></a>'
      }
      return lst
    }
    //</editor-fold>
    // vm.$on('add-pacientes', function(ev, args){
    // })
    function __init__() {
      GetHDsNutricion()
    }
    __init__()
    
    vm.$on('get-hds', (evt, arg) => {
      vm.NHD = false
      vm.UHD = false
      GetHDs()
    })
    vm.$on('back-uhd', (evt, arg) => {
      vm.MP = false
      vm.NP = false
      vm.BP = false
      vm.NC = false
    })
    vm.$on('LOADING', (evt, arg) => {
      vm.IsLoading = true
    })
    vm.$on('NOLOADING', (evt, arg) => {
      vm.IsLoading = false
    })
    // HELPERS
    function GetDistribucion (obj) {
      const dlst = ['Desayuno', 'MM', 'Almuerzo', 'MT', 'Cena', 'MN']
      let indice = 0
      dlst.forEach((x, i) => {
        if (obj[x]){
          indice = i
        }
      })
      return dlst[indice]
    }
    vm.$on('$destroy', function() {
      HDFactory.reset()
      vm = null
      $scope = null
    })
  }
])