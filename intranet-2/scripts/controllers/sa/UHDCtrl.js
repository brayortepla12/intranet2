app.controller('UHDCtrl', ["$scope", "$rootScope", "HDFactory", "HDService",
  function ($scope, $rootScope, HDFactory, HDService) {
    let vm = $scope
    const LIMITE_DIAS = 100
    // VARIABLES
    vm.NUEVOSPACIENTES = 0
    vm.Sectores = []
    vm.Variables = []
    vm.HD = {}
    // ON MENSAJES
    vm.$on('init-uhd', (ev, HDId) => {
      HDFactory.HDId = HDId
      GetHDNyId(HDId)
      GetSectores()
    })
    vm.$on('back-uhd', (evt, arg) => {
      GetHDNyId(HDFactory.HDId)
    })
    vm.$on('reload-hd', (evt, arg) => {
      GetHDNyId(HDFactory.HDId)
    })
    vm.HabilitarPaciente = (i) => {
      const fecha = moment(vm.HD.Fecha)
      const fecha40 = fecha.add(LIMITE_DIAS, 'd')
      if (fecha40.isBefore(moment())) {
        swal('Error', "Esta hoja ya no se puede modificar", 'error')
        return false
      }
      vm.HD.Detalles[i].Continua = !vm.HD.Detalles[i].Continua
    }
    vm.CancelarPedido = (DHDId, Comida, Fecha) => {
      const fecha = moment(Fecha)
      const fecha40 = fecha.add(LIMITE_DIAS, 'd')
      if (fecha40.isBefore(moment())) {
        swal('Error', "Esta hoja ya no se puede modificar", 'error')
        return false
      }
      swal({
        title: `¿Deseas cancelar el pedido ${Comida} de ${DHDId}?`,
        text: "Nota: Solo debe cancelarse el pedido en casos especiales.",
        type: "input",
        showCancelButton: true,
        animation: "slide-from-top",
        inputPlaceholder: "Motivo por el cual cancelas el pedido",
        confirmButtonClass: "btn-success",
        confirmButtonText: "Acepto",
        cancelButtonText: "Cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
      }, function (textvalue) {
        if (textvalue && !vm.IsLoading) {
          if (textvalue === "") {
            swal.showInputError("Debes escribir un motivo!")
            return false
          }
          vm.IsLoading = true;
          const data = {
            DHDId: DHDId,
            Comida: Comida,
            Motivo: textvalue,
            ModifiedBy: $rootScope.username.NombreCompleto,
          };
          const obj = {
            CancelarComida: JSON.stringify(data)
          };
          HDService.putHD(obj).then((d) => {
            if (typeof d.data.data === "string") {
              vm.IsLoading = false;
              swal("Enhorabuena", d.data.data, "success");
              vm.PHD = false;
              vm.OHD = false;
              vm.UHD = false;
              vm.LHD = true;
              vm.HD = {};
              $rootScope.$broadcast('get-hds')
              GetHDs();
            } else {
              vm.IsLoading = false;
              swal("Error", d.data, "error");
            }
          }, () => {
            vm.IsLoading = false;
          }).catch(error => {
            console.error(error.stack || error);
            vm.IsLoading = false;
          });
        }
      });
    }
    // CONSULTAS
    function GetSectores() {
      vm.Sectores = HDFactory.data.Sectores || []
      if (vm.Sectores.length === 0) {
        HDService.getSectores().then(function (c) {
          vm.Sectores = Array.isArray(c.data) ? c.data : []
          HDFactory.data.Sectores = vm.Sectores
        }).catch(error => {
          swal("Error", error.stack || error, "error")
        })
      }
    }

    function GetHDNyId(HDId) {
      vm.NUEVOSPACIENTES = 0
      $rootScope.$broadcast('LOADING')
      HDService.getHDById(HDId).then((d) => {
        $rootScope.$broadcast('NOLOADING')
        if (typeof d.data !== "string") {
          vm.HD = d.data[0]
          vm.HD.Detalles.forEach(x => {
            if (x.Nuevo) {
              x.TipoId = ''
              x.Observacion = ''
              vm.NUEVOSPACIENTES++
            }
          })
          HDFactory.NUEVOSPACIENTES = vm.NUEVOSPACIENTES
          HDFactory.HD = vm.HD
          if (vm.Variables.length === 0) {
            vm.Variables = HDFactory.data.Variables || []
            if (vm.Variables.length === 0) {
              HDService.getVariables().then(function (c) {
                if (!Array.isArray(c.data)) {
                  swal("Error", c.data, "error")
                  return false
                }
                vm.Variables = Array.isArray(c.data) ? c.data : []
                HDFactory.data.Variables = vm.Variables
                // FuncionWatch(d.data[0].Detalles)
              })
            }
          }
        } else {
          swal("Error", d.data, "error")
          vm.UHD = false
        }
      }).catch(e => {
        $rootScope.$broadcast('NOLOADING')
      })
    }
    vm.$on('$destroy', function () {
      vm = null
      $scope = null
    })
  }
])