app.controller('PrintHDCtrl', ["$scope", "$rootScope", "HDFactory", "HDService",
  function ($scope, $rootScope, HDFactory, HDService) {
    let vm = $scope
    // VARIABLES
    // vm.PREFIJO = $state.current.name === "sa.listado_hd_cield" ? 'CIELD' : 'PRADO';
    vm.PREFIJO = 'PRADO';
    vm.TVD = 0;
    vm.TVMM = 0;
    vm.TVA = 0;
    vm.TVMT = 0;
    vm.TVC = 0;
    vm.TVMN = 0;
    vm.Variables = []
    vm.pHD = {}
    vm.ImpDis = ''
    // ON MENSAJES
    vm.$on('load-print', (ev, HD) => {
      GetVariables()
      vm.pHD = HD
      vm.ImpDis = GetDistribucion()
      let Ps = vm.pHD.Detalles.filter(x => x.Estado !== 'Suspendido')
      vm.pHD.Detalles = Ps
    })
    // EVENTOS
    vm.ImprimirHD = () => {
      setTimeout(function () {
        printDiv()
      }, 600)
    }
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
    function printDiv() {
      $("#HD").print({
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
    // WATCHERS
    vm.$watch('pHD.Detalles', (lst) => {
      vm.TVD = 0;
      vm.TVMM = 0;
      vm.TVA = 0;
      vm.TVMT = 0;
      vm.TVC = 0;
      vm.TVMN = 0;
      if (vm.Variables.length === 0) {
          return false;
      }
      for (let i in vm.Variables) {
          vm.Variables[i].TD = 0;
          vm.Variables[i].TMM = 0;
          vm.Variables[i].TA = 0;
          vm.Variables[i].TMT = 0;
          vm.Variables[i].TC = 0;
          vm.Variables[i].TMN = 0;
      }
      for (let i in lst) {
          let Dindex = _.findIndex(vm.Variables, {VariableId: Number(lst[i].DesayunoId)});
          let MMindex = _.findIndex(vm.Variables, {VariableId: Number(lst[i].MMId)});
          let Aindex = _.findIndex(vm.Variables, {VariableId: Number(lst[i].AlmuerzoId)});
          let MTindex = _.findIndex(vm.Variables, {VariableId: Number(lst[i].MTId)});
          let Cindex = _.findIndex(vm.Variables, {VariableId: Number(lst[i].CenaId)});
          let MNindex = _.findIndex(vm.Variables, {VariableId: Number(lst[i].MNId)});
          if (Dindex >= 0) {
              vm.pHD.Detalles[i].Desayuno = vm.Variables[Dindex].Abrv;
              vm.Variables[Dindex].TD++;
              vm.TVD++;
          }
          if (MMindex >= 0) {
              vm.pHD.Detalles[i].MM = vm.Variables[MMindex].Abrv;
              vm.Variables[MMindex].TMM++;
              vm.TVMM++;
          }
          if (Aindex >= 0) {
              vm.pHD.Detalles[i].Almuerzo = vm.Variables[Aindex].Abrv;
              vm.Variables[Aindex].TA++;
              vm.TVA++;
          }
          if (MTindex >= 0) {
              vm.pHD.Detalles[i].MT = vm.Variables[MTindex].Abrv;
              vm.Variables[MTindex].TMT++;
              vm.TVMT++;
          }
          if (Cindex >= 0) {
              vm.pHD.Detalles[i].Cena = vm.Variables[Cindex].Abrv;
              vm.Variables[Cindex].TC++;
              vm.TVC++;
          }
          if (MNindex >= 0) {
              vm.pHD.Detalles[i].MN = vm.Variables[MNindex].Abrv;
              vm.Variables[MNindex].TMN++;
              vm.TVMN++;
          }
      }
    }, true)
    // HELPERS
    function GetDistribucion () {
      const dlst = ['Desayuno', 'MM', 'Almuerzo', 'MT', 'Cena', 'MN']
      let indice = 0
      dlst.forEach((x, i) => {
        if (vm.pHD[x]){
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