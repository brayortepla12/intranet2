'use strict'
app.controller('ghCtrl', [
  '$scope',
  '$rootScope',
  'SesionService',
  function ($scope, $rootScope, SesionService) {
    const url = 'http://localhost:8081/'
    //console.log('data', SesionService.get('UserData_Polivalente'))
    const userData = SesionService.get('UserData_Polivalente')

    // Encrypt url
    const encryptData = CryptoJS.AES.encrypt(
      `auth=${userData.key}&email=${userData.Email}&pId=${userData.PersonaId}&name=${userData.NombreCompleto}&pic=${userData.Url_Foto}`,
      'Elan Bravo'
    )

    // Redirección a Ghumano
    location.href = url + `#/${encryptData.toString()}`
  },
])
