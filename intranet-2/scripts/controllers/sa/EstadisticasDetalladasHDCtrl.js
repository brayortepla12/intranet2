app.controller('EstadisticasDetalladasHDCtrl', ['$scope', '$rootScope', 'HDService',
  function ($scope, $rootScope, HDService) {
    let vm = $scope
    // VARIABLES
    vm.Sectores = []
    vm.Estadisticas = []
    let DATE = moment()
    vm.Estado = 'TODOS'
    vm.Mes = DATE.format('M')
    vm.Year = DATE.format('YYYY')
    vm.Dia = DATE.format('D')
    vm.UltimoDiaMes = Number(DATE.endOf('month').format('DD'))
    vm.Estadisticas = []
    vm.Empresas = []
    vm.Empresa = "TODOEMPRESA"
    vm.ToPrint = false
    vm.PREFIJO = 'PRADO'
    // EVENTOS
    vm.GetEstadisticas = () => {
      let DATE = moment(`01-${vm.Mes}-${vm.Year}`, "DD-MM-YYYY")
      vm.UltimoDiaMes = Number(DATE.endOf('month').format('DD'))
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
      HDService.GetEstadisticasDetalladas(vm.Empresa, vm.Dia, vm.Mes, vm.Year).then(r => {
        vm.Estadisticas = Array.isArray(r.data) ? r.data : []
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
      $("#HED").print({
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