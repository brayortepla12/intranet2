app.controller('PlanillaLideresCtrl', ["$scope", "$rootScope", "LideresService",
    function ($scope, $rootScope, LideresService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        let vm = $scope;
        vm.Mes = moment().format('M');
        vm.Year = moment().format('YYYY');
        vm.Dia = moment().format('D');
        vm.fin_mes = moment(`${vm.Year}-${vm.Mes}-1`).endOf('month').format('DD');
        vm.Tipo = "Entrada";
        vm.TipoTurno = "";
        vm.Listados = [];
        vm.ListadosES = [];
        vm.Lideres = [];
        vm.Dias = [];
        //</editor-fold>
        vm.getLideres = () => {
            vm.Lideres = [];
            GetLideres();
        };
        vm.ImprimirPlantilla = () => {
            printDiv("#PLANILLA");
        };
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetLideres() {
            vm.fin_mes = moment(`${vm.Year}-${vm.Mes}-1`).endOf('month').format('DD');
            LideresService.getESLideres(vm.Year, vm.Mes, vm.Dia).then((d) => {
                console.log(d.data);
                vm.Lideres = d.data;
            });
        }
        function SetDias() {
            vm.Dias = [];
            vm.fin_mes = moment(`${vm.Year}-${vm.Mes}-1`).endOf('month').format('DD');
            for (var i = 1; i <= fin_mes; i++) {
                vm.Dias.push(i + "");
            }
        }
        //</editor-fold>
        function printDiv(id) {
            $(id).print({
                globalStyles: true,
                mediaPrint: false,
                stylesheet: null,
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null,
                manuallyCopyFormValues: true,
                deferred: $.Deferred(),
                timeout: 1500,
                title: null,
                doctype: '<!doctype html>'
            });

            setTimeout(function () {
                vm.ToPrint = false;
                vm.$apply();
            }, 1000);
        }
        function __init__() {
            GetLideres();
        }

        __init__();
    }]);



