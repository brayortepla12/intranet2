'use strict'
app.controller('LoginCtrl', [
  '$scope',
  '$cookies',
  'LoginService',
  'SesionService',
  '$state',
  '$stateParams',
  '$crypto',
  'UsuarioService',
  function (
    $scope,
    $cookies,
    LoginService,
    SesionService,
    $state,
    $stateParams,
    $crypto,
    UsuarioService
  ) {
    //<editor-fold defaultstate="collapsed" desc="inicializar variables">
    let vm = $scope
    vm.CargandoBandera = false
    vm.obj = {
      // objeto para la consulta
      User: '',
      Password: '',
    }
    vm.Admision = ''
    vm.VERSION = '?v=2.8.19'
    //</editor-fold>
    vm.Login = function () {
      if (!vm.CargandoBandera) {
        if (vm.obj.User !== '' && vm.obj.Password !== '') {
          vm.CargandoBandera = true
          LoginService.Login(vm.obj).then(
            function (d) {
              vm.CargandoBandera = false
              if (!d.data.error) {
                SesionService.remove('MenuAPP_Polivalente')
                SesionService.set(d.data.data, 'UserData_Polivalente')
                $cookies.put('_key_data', d.data.data.key)
                $state.go('principal')
              } else {
                swal('Error', d.data.error, 'error')
              }
            },
            function (e) {
              vm.CargandoBandera = false
              swal('Hubo un Error', e, 'error')
            }
          )
        } else {
          swal(
            'Advertencia:',
            'Debe ingresar un usuario y una contraseña validos.',
            'warning'
          )
        }
      }
    }
    vm.VerCama = function () {
      if (vm.Admision !== '') {
        //                $state.go("ver_cuna_ext", {admision: vm.Admision});
        var url = $state.href('ver_cuna_ext', { admision: vm.Admision })
        window.open(url, '_blank')
      }
    }
    vm.ResetPassword = function () {
      UsuarioService.DoResetPass(vm.obj.User).then(function (e) {
        if (e.data === '1') {
          var obj = {
            usuario2: vm.obj.User,
          }
          LoginService.Login(obj).then(function (d) {
            if (d.data === '1') {
              swal(
                'Enhorabuena!',
                'Para continuar con el proceso de restablecimiento de contraseña, revise su correo.',
                'warning'
              )
            } else {
              swal('Hubo un Error', d.data, 'error')
            }
          })
        } else {
          swal('Advertencia:', 'Debe ingresar un usuario valido.', 'warning')
        }
      })
    }

    function AutoLogin(usuarioData) {
      if (moment().diff(datos.FechaCreated, 'minutes') <= 0) {
        let obj = {
          AutoLogin: datos.UsuarioId,
        }
        LoginService.Login(obj).then(
          function (d) {
            if (typeof d.data != 'string') {
              SesionService.remove('MenuAPP_Polivalente')
              SesionService.remove('UserData_Polivalente')
              SesionService.set(d.data.data, 'UserData_Polivalente')
              $state.go('principal')
            } else {
              swal('Error', d.data, 'error')
            }
          },
          function (e) {
            swal('Hubo un Error', e, 'error')
          }
        )
      } else {
        swal('ATENCIÓN', 'TOKEN AUTOMATICO ERRADO O VENCIDO', 'warning')
      }
    }

    function _init_() {
      let host = window.location.hostname
      if (host === '192.168.9.139') {
        swal('ATENCIÓN', 'ESTAS EN MODO PRUEBAS', 'warning')
        vm.PRUEBAS = true
      }
      let obj = $stateParams.UsuarioId || null
      if (obj) {
        let datos = $crypto.decrypt(obj, 'Franklin Ospino')
        if (datos) {
          datos = JSON.parse(datos)
          AutoLogin(datos)
        }
      }
    }
    _init_()
  },
])
