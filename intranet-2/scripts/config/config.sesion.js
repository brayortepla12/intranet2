app.run([
  '$rootScope',
  'SesionService',
  '$state',
  function ($rootScope, SesionService, $state) {
    var lastDigestRun = new Date()
    $rootScope.$watch(function detectIdle() {
      var now = new Date()
      var currentState = $state.current.name
    
      if (
        now - lastDigestRun > 60 * 60 * 15200 &&
        currentState !== 'controlturno_pantalla'
      ) {
        // logout here, like delete cookie, navigate to login ...
        SesionService.remove('UserData_Polivalente')
        SesionService.remove('MenuAPP_Polivalente')
        $state.go('login')
      }
      lastDigestRun = now
    })
  }
])

app.config([
  '$sceDelegateProvider',
  function ($sceDelegateProvider) {
    $sceDelegateProvider.resourceUrlWhitelist(['**'])
  }
])
