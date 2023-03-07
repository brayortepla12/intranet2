app.controller('SolicitudAdminNotificacionCtrl', [
  '$scope',
  '$rootScope',
  'SolicitudService',
  function ($scope, $rootScope, SolicitudService) {
    let vm = $scope
    vm.IsPolivalente = $rootScope.username.IsPolivalente
    vm.IsSistemas = $rootScope.username.IsSistemas
    vm.IsBiomedico = $rootScope.username.IsBiomedico
    vm.TotalSolicitudes = 0
    vm.TotalBiomedico = 0
    vm.TotalPolivalente = 0
    vm.TotalSistemas = 0
    vm.HASDATASOL = false
    let url = '/intranet-2/public_html/cancion/alerta.mp3?v=2.8.19'
    vm.audio = null
    //        Pusher.logToConsole = true;

    var pusher = new Pusher('82b644826921442b1ad3', {
      cluster: 'us2',
    })

    /**
     * Subscribe to pusher
     */
    var channel = pusher.subscribe('solicitud')
    channel.bind('new-solicitud', function (data) {
      if (vm.IsPolivalente && data.prefijo == 'pol') {
        toastr.success(data.msg, data.Nombres + ' dice: ', {
          timeOut: 35000,
          onHidden: vm.DisableAudio,
          onCloseClick: vm.DisableAudio,
        })
        SetAudio()
        _init()
      }

      if (vm.IsSistemas && data.prefijo == 'sistemas') {
        toastr.success(data.msg, data.Nombres + ' dice: ', {
          timeOut: 35000,
          onHidden: vm.DisableAudio,
          onCloseClick: vm.DisableAudio,
        })
        SetAudio()
        _init()
      }

      if (vm.IsBiomedico && data.prefijo == 'biomedicos') {
        toastr.success(data.msg, data.Nombres + ' dice: ', {
          timeOut: 35000,
          onHidden: vm.DisableAudio,
          onCloseClick: vm.DisableAudio,
        })
        SetAudio()
        _init()
      }
    })

    vm.DisableAudio = () => {
      vm.audio.pause()
      vm.audio.currentTime = 0
      vm.HASDATASOL = false
      vm.$apply()
    }
    /**
     * Audio notificacion
     */
    function SetAudio() {
      vm.audio = new Audio(url)
      vm.audio.load()
      if (!vm.HASDATASOL) {
        vm.audio.play()
      } else {
        vm.HASDATASOL = true
        vm.audio.onended = function () {
          vm.HASDATASOL = false
          vm.$apply()
        }
        vm.audio.ontimeupdate = function () {
          if (vm.audio.currentTime == vm.audio.duration) {
            vm.HASDATASOL = false
            vm.$apply()
          }
        }
      }
    }
    function _init() {
      if (vm.IsPolivalente || vm.IsSistemas || vm.IsBiomedico) {
        SolicitudService.GetTotalSolicitudesActivas().then(d => {
          if (d.data.length > 0) {
            vm.TotalPolivalente = vm.IsPolivalente
              ? d.data[0].TotalPolivalente
              : 0
            vm.TotalBiomedico = vm.IsBiomedico ? d.data[0].TotalBiomedico : 0
            vm.TotalSistemas = vm.IsSistemas ? d.data[0].TotalSistemas : 0
            vm.TotalSolicitudes =
              vm.TotalPolivalente + vm.TotalBiomedico + vm.TotalSistemas
          }
        })
      }
    }
    _init()
  },
])
