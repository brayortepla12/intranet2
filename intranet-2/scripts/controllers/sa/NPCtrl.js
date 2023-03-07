app.controller('NPCtrl', ["$scope", "$rootScope", "HDFactory", "HDService",
  function ($scope, $rootScope, HDFactory, HDService) {
    // VARIABLES
    let vm = $scope
    vm.TOTALC = 0
    vm.PHD = {}
    vm.Variables = []
    // ON MENSAJES
    vm.$on('view-new-paciente', (ev, NP) => {
      vm.TOTALC = 0
      vm.PHD = NP
      vm.PHD.Detalles = vm.PHD.Detalles.filter(x => x.Seleccionado)
      vm.PHD.Distribucion = GetDistribucion()
      GetVariables()
    })
    vm.$on('add-paciente', (e, d) => {
      let r = [vm.TOTALC].some(function (val) {
        return val > 0
      })
      let Verificar = []
      Verificar = vm.PHD.Detalles.filter(x => {
        return x.Seleccionado && !x.TipoId
      })
      if (vm.PHD.Detalles.length === 0 || !r) {
        swal("Error", "No hay pacientes por añadir", "error")
        return false
      } else if (Verificar.length > 0) {
        swal("Error", "Tienes un paciente seleccionado sin seleccionar el tipo de alimento, por favor desactivelo", "error")
        return false
      } else {
        let obj = {
          PHD: JSON.stringify(vm.PHD)
        }
        $rootScope.$broadcast('LOADING')
        HDService.postHD(obj).then((d) => {
          $rootScope.$broadcast('NOLOADING')
          if (typeof d.data !== "string") {
            swal("Enhorabuena", "Se ha guardado los datos con exito", "success")
            vm.PHD = {
              Fecha: moment().format('YYYY-MM-DD'),
              Hora: moment().format('HH:mm:ss'),
              // Tipo: ValidarFecha(),
              Sector: "",
              Detalles: [],
              Responsable: $rootScope.username.NombreCompleto,
              UResponsableId: $rootScope.username.UserId,
              ResponsableId: $rootScope.username.PersonaId,
              CreatedBy: $rootScope.username.NombreCompleto
            }
            $rootScope.$broadcast('back-uhd')
          } else {
            swal("Error", d.data, "error")
          }
        }).catch(e => {
          $rootScope.$broadcast('NOLOADING')
        })
      }
    })
    // CONSULTAS
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
    // WATCHERS
    vm.$watch('PHD.Detalles', (lst) => {
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
    // HELPERS
    function GetDistribucion () {
      const dlst = ['Desayuno', 'MM', 'Almuerzo', 'MT', 'Cena', 'MN']
      let indice = 0
      dlst.forEach((x, i) => {
        if (vm.PHD[x]){
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