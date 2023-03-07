app.controller('PlanillaCtrl', ["$scope", "$rootScope","$stateParams","RondaService","EmpresaService",
    function ($scope, $rootScope,$stateParams, RondaService,EmpresaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Planilla = null;
        $scope.Empresa = null;
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.Imprimir = function () {
            $scope.ToPrint = true;
            setTimeout(function () {
                printDiv();
            }, 600);

        };
        //</editor-fold>
        
        function GetRondaById() {
            RondaService.GetRondaById($stateParams.planilla_id).then(function(d){
                console.log(d.data)
                $scope.Planilla = d.data[0];
            });
        }
        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                $scope.Empresa = e.data;
            });
        }
        
        
        function printDiv() {
            $("#Planilla").print({
                globalStyles: true,
                mediaPrint: false,
                stylesheet: null,
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null,
                manuallyCopyFormValues: true,
                deferred: $.Deferred(),
                timeout: 750,
                title: null,
                doctype: '<!doctype html>'
            });

            setTimeout(function () {
                $scope.ToPrint = false;
                $scope.$apply();
            }, 1000);
        }
        
        function _init() {
            GetEmpresa();
            GetRondaById();
        }
        _init();
    }]);




