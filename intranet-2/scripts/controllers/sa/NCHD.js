app.controller('NCHDtrl', ['$scope', '$rootScope', 'HDFactory', 'HDService',
  function ($scope, $rootScope, HDFactory, HDService) {
    let vm = $scope
    // VARIABLES
    vm.TOTALC = 0
    vm.Sectores = HDFactory.data.Sectores || []
    vm.Variables = HDFactory.data.Variables || []
    vm.NCHD = {}
    vm.SELECTALL = true
    // ON MENSAJES
    vm.$on('nc-view', (ev, NCHD) => {
      GetSectores()
      GetVariables()
      vm.NCHD = NCHD
    })
    vm.SelectAll = () => {
      vm.SELECTALL = !vm.SELECTALL
      vm.NCHD.Detalles.forEach(x => {
        x.Seleccionado = vm.SELECTALL
      });
    }
    vm.$on('guardar-comida', (e, a) => {
      let r = [vm.TOTALC].some(function (val) {
        return val > 0
      })
      let Verificar = []
      Verificar = vm.NCHD.Detalles.filter(x => {
        return x.Seleccionado && !x.TipoId
      })
      if (vm.NCHD.Detalles.length === 0 || !r) {
        swal("Error", "La hoja no puede ir en blanco", "error")
        return false
      } else if (Verificar.length > 0) {
        swal("Error", "Tienes un paciente seleccionado sin seleccionar el tipo de alimento, por favor desactivelo", "error")
        return false
      } else {
        const data = angular.copy(vm.NCHD)
        _.remove(data.Detalles, d => {
          return !d.Seleccionado
        })
        data.Responsable = $rootScope.username.NombreCompleto
        data.UResponsableId = $rootScope.username.UserId
        data.ResponsableId = $rootScope.username.PersonaId
        data.CreatedBy = $rootScope.username.NombreCompleto
        let obj = {
          SolicitarComida: JSON.stringify(data)
        }
        $rootScope.$broadcast('LOADING')
        HDService.postHD(obj).then((d) => {
          $rootScope.$broadcast('NOLOADING')
          if (typeof d.data !== "string") {
            swal("Enhorabuena", "Se ha guardado los datos con exito", "success")
            $rootScope.$broadcast('back-uhd')
          } else {
            swal("Error", d.data, "error")
          }
        }).catch(e => {
          $rootScope.$broadcast('NOLOADING')
        })
      }
    })
    // WATCHERS
    vm.$watch('NCHD.Detalles', (lst) => {
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
          VariableId: Number(lst[i].TipoId)
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
      if (vm.Variables.length === 0) {
        HDService.getVariables().then(function (c) {
          vm.Variables = Array.isArray(c.data) ? c.data : []
          HDFactory.data.Variables = vm.Variables
        }).catch(error => {
          swal('Error', error.stack || error, 'error')
        })
      }
    }
    vm.$on('$destroy', function() {
      vm = null
      $scope = null
    })
  }
])