app.controller('EstadisticasHDCtrl', ['$scope', '$rootScope', 'HDService',
  function ($scope, $rootScope, HDService) {
    let vm = $scope
    vm.Sectores = []
    vm.Estadisticas = []
    // VARIABLES
    let DATE = moment()
    vm.Mes = DATE.format('M')
    vm.Year = DATE.format('YYYY')
    vm.Estadisticas = []
    vm.Empresa = "TODOEMPRESA"
    vm.ToPrint = false
    vm.PREFIJO = 'PRADO'
    //EVENTOS
    vm.GetEstadisticas = () => {
      GetEstadisticas()
    }
    vm.Imprimir = () => {
      setTimeout(function () {
        printDiv()
      }, 600)
    }
    // CONSULTAS
    function GetEstadisticas() {
      vm.Estadisticas = []
      HDService.GetEstadisticas(vm.Empresa, vm.Mes, vm.Year).then(r => {
        vm.Estadisticas = r.data
      })
    }
    function GetEmpresas() {
      vm.Empresas = []
      HDService.getEmpresas().then(r => {
        vm.Empresas = Array.isArray(r.data) ? r.data : []
      })
    }
    // HELPERS
    function printDiv() {
      $("#HE").print({
        globalStyles: false,
        mediaPrint: false,
        stylesheet: "/intranet-2/public_html/styles/hd.css",
        noPrintSelector: ".no-print",
        iframe: true,
        append: null,
        prepend: null,
        manuallyCopyFormValues: true,
        deferred: $.Deferred(),
        timeout: 1500,
        title: null,
        doctype: '<!doctype html>'
      })

      setTimeout(function () {
        vm.ToPrint = false
        vm.$apply()
      }, 1000)
    }
    function __init__() {
      GetEmpresas()
      GetEstadisticas()
    }
    __init__()
    vm.$on('$destroy', function() {
      vm = null
      $scope = null
    })
  }
])