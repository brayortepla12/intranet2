app.controller('AuthViaticoCtrl', ["$scope", "$rootScope", "ViaticoService", "SedeService", "SesionService",
  function ($scope, $rootScope, ViaticoService, SedeService, SesionService) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    let vm = $scope
    vm.ResFirma = ''
    const u = SesionService.get('UserData_Polivalente')
    if (u != undefined) {
      $rootScope.username = u
    }
    

    vm.AutorizarSolicitud = () => {
      swal({
        title: '¿Deseas dar tu visto bueno?',
        text: 'Nota: una vez dado el visto bueno, no se puede deshacer!',
        type: 'warning',
        showCancelButton: true,
        confirmButtonClass: 'btn-success',
        confirmButtonText: 'SI',
        cancelButtonText: 'NO',
        closeOnConfirm: false,
        closeOnCancel: true
      }, function (isConfirm) {
        if (isConfirm) {
          const signature = vm.accept()
          console.log(signature)
          if (!signature.dataUrl) {
            swal("Error!", 'Debe ingresar una firma en el recuadro blanco', 'error')
            return false
          }
          window.Solicitud.ResFirma = signature.dataUrl
          window.opener.notify(JSON.stringify(window.Solicitud))
          window.close()
        }
      })
    }
    vm.VolverFirmar = () => {
      Firmar()
    }

    function Firmar() {
      vm.ResFirma = ''
      window.SigCapt.init({
        licenceString: "eyJhbGciOiJSUzUxMiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiI3YmM5Y2IxYWIxMGE0NmUxODI2N2E5MTJkYTA2ZTI3NiIsImV4cCI6MjE0NzQ4MzY0NywiaWF0IjoxNTYwOTUwMjcyLCJyaWdodHMiOlsiU0lHX1NES19DT1JFIiwiU0lHQ0FQVFhfQUNDRVNTIl0sImRldmljZXMiOlsiV0FDT01fQU5ZIl0sInR5cGUiOiJwcm9kIiwibGljX25hbWUiOiJTaWduYXR1cmUgU0RLIiwid2Fjb21faWQiOiI3YmM5Y2IxYWIxMGE0NmUxODI2N2E5MTJkYTA2ZTI3NiIsImxpY191aWQiOiJiODUyM2ViYi0xOGI3LTQ3OGEtYTlkZS04NDlmZTIyNmIwMDIiLCJhcHBzX3dpbmRvd3MiOltdLCJhcHBzX2lvcyI6W10sImFwcHNfYW5kcm9pZCI6W10sIm1hY2hpbmVfaWRzIjpbXX0.ONy3iYQ7lC6rQhou7rz4iJT_OJ20087gWz7GtCgYX3uNtKjmnEaNuP3QkjgxOK_vgOrTdwzD-nm-ysiTDs2GcPlOdUPErSp_bcX8kFBZVmGLyJtmeInAW6HuSp2-57ngoGFivTH_l1kkQ1KMvzDKHJbRglsPpd4nVHhx9WkvqczXyogldygvl0LRidyPOsS5H2GYmaPiyIp9In6meqeNQ1n9zkxSHo7B11mp_WXJXl0k1pek7py8XYCedCNW5qnLi4UCNlfTd6Mk9qz31arsiWsesPeR9PN121LBJtiPi023yQU8mgb9piw_a-ccciviJuNsEuRDN3sGnqONG3dMSA"
      });
      $(window).on('sigCapt:renderBitmap', function (e, imageBase64) {
        console.log('Signature captured:', imageBase64);
        vm.ResFirma = imageBase64;
        vm.$apply()
      });
      window.SigCapt.capture(
        `${window.Solicitud.ANombreDe}`,
        'Firma digitalmente'
      );
    }

    function __init__() {
      if (!window.Solicitud) {
        window.close()
      }
      setTimeout(() => {
        Firmar()
      }, 2000)
    }
    __init__()
  }
])