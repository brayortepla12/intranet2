app.controller('MPCtrl', ['$scope', '$rootScope', 'HDFactory', 'HDService',
  function ($scope, $rootScope, HDFactory, HDService) {
    let vm = $scope
    // VARIABLES
    vm.TOTALC = 0
    vm.Sectores = HDFactory.data.Sectores || []
    vm.Variables = HDFactory.data.Variables || []
    vm.MPHD = {}
    // ON MENSAJES
    vm.$on('mod-view-paciente', (ev, MPHD) => {
      vm.Sectores = HDFactory.data.Sectores || []
      vm.Variables = HDFactory.data.Variables || []
      GetSectores()
      GetVariables()
      vm.MPHD = MPHD
      vm.MPHD.Distribucion = GetDistribucion()
      vm.ChangeDistribucionMP()
    })
    vm.$on('update-paciente', (e,d) => {
      let r = [vm.TOTALC].some(function (val) {
        return val > 0
      })
      let Verificar = []
      Verificar = vm.MPHD.Detalles.filter(x => {
        return x.Seleccionado && !x.NuevoTipoId
      })
      if (vm.MPHD.Detalles.length === 0 || !r) {
        swal('Error', 'La hoja no puede ir en blanco', 'error')
        return false
      } else if (Verificar.length > 0) {
        swal('Error', 'Tienes un paciente seleccionado sin seleccionar el tipo de alimento, por favor desactivelo', 'error')
        return false
      } else {
        vm.MPHD.ModifiedBy = $rootScope.username.NombreCompleto
        let obj = {
          UPHD: JSON.stringify(vm.MPHD)
        }
        vm.IsLoading = true
        $rootScope.$broadcast('LOADING')
        HDService.putHD(obj).then((d) => {
          $rootScope.$broadcast('NOLOADING')
          if (typeof d.data !== 'string') {
            swal('Enhorabuena', 'Se han modificado los datos con exito', 'success')
            vm.MP = false
            vm.MPHD = {
              Fecha: moment().format('YYYY-MM-DD'),
              Hora: moment().format('HH:mm:ss'),
              // Tipo: ValidarFecha(),
              Sector: '',
              Detalles: [],
              Responsable: $rootScope.username.NombreCompleto,
              UResponsableId: $rootScope.username.UserId,
              ResponsableId: $rootScope.username.PersonaId,
              CreatedBy: $rootScope.username.NombreCompleto
            }
            $rootScope.$broadcast('back-uhd')
            vm.IsLoading = false
          } else {
            vm.IsLoading = false
            swal('Error', d.data, 'error')
          }
        }).catch(e => {
          $rootScope.$broadcast('NOLOADING')
        })
      }
    }) 
    // EVENTOS
    vm.ChangeDistribucionMP = () => {
      vm.MPHD.Detalles.forEach(x => {
        if (!x.Nuevo) {
          switch (vm.MPHD.Distribucion) {
            case 'Desayuno':
              x.NuevoTipoId = x.DesayunoId
              x.ObservacionComida = x.ODesayuno
              break
            case 'MM':
              x.NuevoTipoId = x.MMId
              x.ObservacionComida = x.OMM
              break
            case 'Almuerzo':
              x.NuevoTipoId = x.AlmuerzoId
              x.ObservacionComida = x.OAlmuerzo
              break
            case 'MT':
              x.NuevoTipoId = x.MTId
              x.ObservacionComida = x.OMT
              break
            case 'Cena':
              x.NuevoTipoId = x.CenaId
              x.ObservacionComida = x.OCena
              break
            case 'MN':
              x.NuevoTipoId = x.MNId
              x.ObservacionComida = x.OMN
              break
            default:
              break
          }
        }
      })
    }
    // WATCHERS
    vm.$watch('MPHD.Detalles', (lst) => {
      vm.TOTALC = 0
      if (vm.Variables.length === 0) {
        return false
      }
      for (let i in vm.Variables) {
        vm.Variables[i].TOTALC = 0
      }
      for (let i in lst) {
        if (!lst[i].Seleccionado) {
          continue // saltamos los que no estan seleccionados
        }
        let Vindex = _.findIndex(vm.Variables, {
          VariableId: Number(lst[i].NuevoTipoId)
        })
        if (Vindex >= 0) {
          lst[i].Tipo = vm.Variables[Vindex].Abrv
          vm.Variables[Vindex].TOTALC++
          vm.TOTALC++
        }
      }
    }, true)

    // CONSULTAS
    function GetSectores() {
      vm.Sectores = HDFactory.data.Sectores || []
      if (vm.Sectores.length === 0) {
        HDService.getSectores().then(function (c) {
          vm.Sectores = Array.isArray(c.data) ? c.data : []
          HDFactory.data.Sectores = vm.Sectores
        }).catch(error => {
          swal('Error', error.stack || error, 'error')
        })
      }
    }
    function GetVariables() {
      vm.Variables = HDFactory.data.Variables || []
      if (vm.Variables.length === 0) {
        HDService.getVariables().then(function (c) {
          vm.Variables = Array.isArray(c.data) ? c.data : []
          HDFactory.data.Variables = vm.Variables
        }).catch(error => {
          swal('Error', error.stack || error, 'error')
        })
      }
    }
    // HELPERS
    function GetDistribucion () {
      const dlst = ['Desayuno', 'MM', 'Almuerzo', 'MT', 'Cena', 'MN']
      let indice = 0
      dlst.forEach((x, i) => {
        if (vm.MPHD[x]){
          indice = i
        }
      })
      return dlst[indice]
    }
    vm.$on('$destroy', function() {
      vm = null
      $scope = null
    })
  }
])